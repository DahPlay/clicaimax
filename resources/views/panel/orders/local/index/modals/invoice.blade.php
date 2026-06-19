@php
    use App\Enums\BillingTypeAsaasEnum;
    use App\Enums\PaymentStatusOrderAsaasEnum;

    $statusBadge = function (?string $status): array {
        return match ($status) {
            'RECEIVED', 'RECEIVED_IN_CASH', 'CONFIRMED' => ['success', 'PAGO'],
            'PENDING'                                   => ['warning', 'PENDENTE'],
            'AWAITING_RISK_ANALYSIS'                    => ['info',    'EM ANÁLISE'],
            'OVERDUE'                                   => ['danger',  'VENCIDA'],
            'REFUNDED', 'REFUND_REQUESTED'              => ['secondary','ESTORNADA'],
            'CHARGEBACK_REQUESTED', 'CHARGEBACK_DISPUTE','AWAITING_CHARGEBACK_REVERSAL' => ['secondary','CHARGEBACK'],
            'DUNNING_REQUESTED', 'DUNNING_RECEIVED'     => ['secondary','COBRANÇA JUDICIAL'],
            null, ''                                    => ['light',    'N/D'],
            default                                     => ['secondary', $status],
        };
    };

    $billingLabel = function (?string $type) {
        if (!$type) return '—';
        return BillingTypeAsaasEnum::tryFrom($type)?->getName() ?? $type;
    };
@endphp

<div class="modal-header bg-gradient text-white" style="background:linear-gradient(90deg,#1d4ed8 0%,#2563eb 100%);">
    <h5 class="modal-title">
        <i class="fa fa-file-invoice-dollar mr-2"></i>
        Detalhes da Fatura — Pedido #{{ $order?->id ?? '-' }}
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    @if (!$order)
        <div class="alert alert-danger mb-0">Pedido não encontrado.</div>
    @elseif (!$order->subscription_asaas_id)
        <div class="alert alert-warning mb-0">
            Este pedido ainda não possui assinatura criada no Asaas.
        </div>
    @else
        <div class="mb-3">
            <strong>{{ $order->customer->name ?? '—' }}</strong>
            <span class="text-muted">— Plano {{ $order->plan->name ?? '—' }}</span>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">ASSINATURA ASAAS</small>
                    <span class="font-weight-bold">{{ $order->subscription_asaas_id }}</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">CLIENTE ASAAS</small>
                    <span class="font-weight-bold">{{ $order->customer_asaas_id ?? '—' }}</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">VALOR</small>
                    <span class="font-weight-bold">R$ {{ number_format((float) ($subscription['value'] ?? $order->value), 2, ',', '.') }}</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">PRÓXIMO VENCIMENTO</small>
                    <span class="font-weight-bold">
                        @php $next = $subscription['nextDueDate'] ?? $order->next_due_date; @endphp
                        {{ $next ? \Carbon\Carbon::parse($next)->format('d/m/Y') : '—' }}
                    </span>
                </div>
            </div>
        </div>

        @php
            $currentBilling = $subscription['billingType'] ?? $order->getRawOriginal('billing_type');
        @endphp
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#eef2ff;">
                        <i class="fa fa-credit-card text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">FORMA DE PAGAMENTO</small>
                        <span class="font-weight-bold">{{ $billingLabel($currentBilling) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-2">
                <strong><i class="fa fa-history mr-2 text-muted"></i>Histórico de Pagamentos</strong>
                <span class="badge badge-secondary ml-1">{{ $payments->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if ($payments->isEmpty())
                    <div class="alert alert-info m-3 mb-0">
                        Nenhuma fatura encontrada no Asaas para esta assinatura.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr class="text-muted">
                                    <th>Vencimento</th>
                                    <th>Pagamento</th>
                                    <th class="text-right">Valor</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th class="text-right">Fatura</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $p)
                                    @php [$color, $label] = $statusBadge($p->status); @endphp
                                    <tr>
                                        <td>{{ optional($p->due_date)->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ optional($p->payment_date ?? $p->client_payment_date)->format('d/m/Y') ?? '—' }}</td>
                                        <td class="text-right">R$ {{ number_format((float) $p->value, 2, ',', '.') }}</td>
                                        <td>{{ $billingLabel($p->billing_type) }}</td>
                                        <td><span class="badge badge-{{ $color }}">{{ $label }}</span></td>
                                        <td class="text-right">
                                            @php
                                                $invoiceUrl = $p->invoice_url
                                                    ?: ($faturaBase ? $faturaBase . '/i/' . str_replace('pay_', '', $p->payment_asaas_id) : null);
                                            @endphp
                                            @if ($invoiceUrl)
                                                <a href="{{ $invoiceUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-external-link-alt mr-1"></i> Abrir
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
</div>
