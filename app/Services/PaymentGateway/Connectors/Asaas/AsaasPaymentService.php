<?php

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Enums\StatusOrderAsaasEnum;
use App\Jobs\BackOrderOldPlanJob;
use App\Jobs\updateSubscriptionAfterProportionalPayJob;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Package;
use App\Services\AppIntegration\PlanCancelService;
use App\Services\AppIntegration\PlanCreateService;
use App\Services\YouCast\Plan\PlanHistory;
use App\Services\YouCast\Plan\PlanList;
use Illuminate\Support\Facades\Log;

class AsaasPaymentService
{
    public function processEvent(string $event, array $data): bool
    {
        $paymentId = $data['payment']['id'];
        $customerId = $data['payment']['customer'];
        $subscriptionId = $data['payment']['subscription'];
        $paymentStatus = $data['payment']['status'];
        $dueDate = $data['payment']['dueDate'];
        $paymentDate = $data['payment']['paymentDate'];

        $order = Order::where('subscription_asaas_id', $subscriptionId)->first();

        Log::info('AsaasPaymentService acionado');
        if (!$order) {
            Log::warning("Ordem não encontrada para a assinatura $subscriptionId no evento $event.");
            return false;
        }

        if ($event !== 'PAYMENT_DELETED') {
            OrderPayment::upsertFromAsaas($order->id, $data['payment']);
        } else {
            OrderPayment::where('payment_asaas_id', $paymentId)->delete();
        }

        switch ($event) {
            case 'PAYMENT_RECEIVED':
//para aplicar o cupom de desconto somente na primeira mensalidade, descomente abaixo
                if ($order->changed_plan /*|| $order->value != $plan->value*/) {
                    updateSubscriptionAfterProportionalPayJob::dispatch($order);
                }
                $order->update([
                    'status' => StatusOrderAsaasEnum::ACTIVE,
                    'payment_asaas_id' => $paymentId,
                    'payment_status' => $paymentStatus,
                    'next_due_date' => $dueDate,
                    'payment_date' => $paymentDate,
                ]);

                Log::info("Pagamento confirmado para a ordem {$order->id}.");

                // Pacotes que o cliente passa a ter direito após o pagamento.
                $packagesToCreate = [];
                //este if impede que seja enviado cupom de desconto durante a troca de plano
                // remova o if depois de implementar cupom na troca de plano
                if (!$order->changed_plan) {
                    $customer = \App\Models\Customer::find($order->customer_id);
                    if ($customer && $customer->coupon_id != null) {
                        $coupon = Coupon::find($customer->coupon_id);
                        if ($coupon) {
                            $packagesToCreate[] = $coupon->cod;
                        }
                    }
                }
                foreach ($order->plan->packagePlans as $packagePlan) {
                    $pack = Package::find($packagePlan->package_id);
                    if ($pack) {
                        $packagesToCreate[] = $pack->cod;
                    }
                };

                $suspension = $this->getSuspension();
                $planInYoucast = (new PlanHistory())->handle($order->customer->viewers_id);
                $youcastList = $planInYoucast['response'] ?? [];

                // 1) Remove o pacote de suspensão, se estiver ativo no YouCast.
                if ($suspension && is_array($youcastList)) {
                    foreach ($youcastList as $item) {
                        if ($item['viewers_bouquets_products_id'] == $suspension->cod && $item['viewers_bouquets_cancelled'] == 0) {
                            (new PlanCancelService([$suspension->cod], $order->customer->viewers_id))->cancelPlan();
                            break;
                        }
                    }
                }

                // 2) Cria/reativa os pacotes do pedido pago. Roda sempre — se o cliente
                //    foi criado no YouCast sem pacotes (porque não havia suspensão
                //    cadastrada), o histórico vem vazio mas os pacotes precisam ser
                //    adicionados agora.
                if (!empty($packagesToCreate)) {
                    (new PlanCreateService($packagesToCreate, $order->customer->viewers_id))->createPlan();
                }
                break;

            case 'PAYMENT_CREATED':
                $order->update([
                    'payment_asaas_id' => $paymentId,
                    'payment_status' => $paymentStatus,
                    'next_due_date' => $dueDate,
                ]);

                Log::info("Pagamento criado para a ordem {$order->id}.");
                break;

            case 'PAYMENT_CONFIRMED':
                $order->update([
                    'payment_status' => $paymentStatus,
                ]);

                Log::info("Pagamento criado para a ordem {$order->id}.");

                break;

            case 'PAYMENT_OVERDUE':

                if ($order->changed_plan) {
                    BackOrderOldPlanJob::dispatch($order);
                    break;
                }
                $order->update(
                    ['status' => 'INACTIVE'],
                    ['payment_status' => $paymentStatus]
                );

                Log::warning("Pagamento atrasado para a ordem {$order->id}.");

                $youcast = (new PlanList)->handle($order->customer->viewers_id);

                if ($youcast["status"] == 1) {
                    $packagesToCancel = [];
                    foreach ($order->plan->packagePlans as $packagePlan) {
                        $pack = Package::find($packagePlan->package_id);
                        $packagesToCancel[] = $pack->cod;
                    };
                    (new PlanCancelService($packagesToCancel, $order->customer->viewers_id))->cancelPlan();

                    //adiciona o pacote de suspensão
                    $suspension = $this->getSuspension();
                    if ($suspension) {
                        $packagesToCreate = [$suspension->cod];
                        (new PlanCreateService($packagesToCreate, $order->customer->viewers_id))->createPlan();
                    }
                }

                break;

            case 'PAYMENT_DELETED':
                $order->update(['payment_status' => $paymentStatus]);

                Log::info("AssasPaymentService - linha 91 - Pagamento cancelado para a ordem {$order->id}.");

                break;

            default:
                Log::info("Evento de pagamento não tratado: $event");
                return false;
        }

        return true;
    }

    private function getSuspension(): ?Package
    {
        return (new Package())->getSuspensionPackage();
    }
}
