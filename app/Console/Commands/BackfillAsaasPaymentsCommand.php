<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Console\Command;
use Throwable;

class BackfillAsaasPaymentsCommand extends Command
{
    protected $signature = 'asaas:backfill-payments
                            {--order= : ID local de um pedido específico para reprocessar}
                            {--limit=0 : Limita a quantidade de pedidos processados (0 = todos)}';

    protected $description = 'Sincroniza o histórico de pagamentos do Asaas para a tabela order_payments.';

    public function handle(): int
    {
        $gateway = new Gateway(new AsaasConnector());

        $query = Order::query()->whereNotNull('subscription_asaas_id');

        if ($orderId = $this->option('order')) {
            $query->where('id', $orderId);
        }

        if (($limit = (int) $this->option('limit')) > 0) {
            $query->limit($limit);
        }

        $total      = (clone $query)->count();
        $processed  = 0;
        $payments   = 0;
        $failed     = 0;

        if ($total === 0) {
            $this->warn('Nenhum pedido com subscription_asaas_id encontrado.');
            return self::SUCCESS;
        }

        $this->info("Processando {$total} pedidos...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->orderBy('id')->chunkById(50, function ($orders) use ($gateway, &$processed, &$payments, &$failed, $bar) {
            foreach ($orders as $order) {
                try {
                    $response = $gateway->subscription()->getPayments($order->subscription_asaas_id);

                    if (isset($response['error'])) {
                        $failed++;
                        $this->newLine();
                        $this->warn("Pedido #{$order->id} ({$order->subscription_asaas_id}): " . json_encode($response['error']));
                        $bar->advance();
                        continue;
                    }

                    $items = $response['data'] ?? [];
                    foreach ($items as $payment) {
                        if (!empty($payment['id'])) {
                            OrderPayment::upsertFromAsaas($order->id, $payment);
                            $payments++;
                        }
                    }

                    $processed++;
                } catch (Throwable $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("Erro no pedido #{$order->id}: {$e->getMessage()}");
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Pedidos processados: {$processed}");
        $this->info("Pagamentos sincronizados: {$payments}");
        if ($failed > 0) {
            $this->warn("Falhas: {$failed}");
        }

        return self::SUCCESS;
    }
}
