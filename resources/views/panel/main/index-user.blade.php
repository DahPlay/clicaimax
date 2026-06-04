@extends("$routeAmbient.template.index")

@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <style>
                    .du-hero {
                        background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
                        color: #fff;
                        border-radius: 14px;
                        padding: 26px 28px;
                        margin-bottom: 22px;
                        box-shadow: 0 6px 18px rgba(0,0,0,.10);
                    }
                    .du-hero .greet { font-size: 14px; opacity: .85; margin-bottom: 4px; font-weight: 600; letter-spacing: .3px; text-transform: uppercase; }
                    .du-hero .name  { font-size: 26px; font-weight: 800; line-height: 1.15; margin: 0; }
                    .du-hero .sub   { font-size: 13px; opacity: .9; margin-top: 6px; }

                    .du-plan-card {
                        background: #fff;
                        border-radius: 14px;
                        padding: 22px;
                        margin-bottom: 22px;
                        box-shadow: 0 4px 14px rgba(0,0,0,.06);
                        position: relative;
                    }
                    .du-plan-card h6  { color: #6c757d; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
                    .du-plan-card .plan-name { font-size: 22px; font-weight: 800; color: #0d47a1; margin: 4px 0 8px; }
                    .du-plan-card .plan-meta { color: #495057; font-size: 13px; }
                    .du-plan-card .badge-status {
                        position: absolute; top: 22px; right: 22px;
                        padding: 5px 14px; border-radius: 999px;
                        font-size: 11px; font-weight: 700; letter-spacing: .4px;
                    }
                    .badge-status.is-active   { background: #c8e6c9; color: #0d47a1; }
                    .badge-status.is-pending  { background: #fff3cd; color: #856404; }
                    .badge-status.is-canceled { background: #f8d7da; color: #721c24; }

                    .du-shortcut {
                        background: #fff;
                        border-radius: 14px;
                        padding: 22px 16px;
                        text-align: center;
                        box-shadow: 0 4px 14px rgba(0,0,0,.06);
                        cursor: pointer;
                        transition: transform .18s ease, box-shadow .18s ease;
                        text-decoration: none !important;
                        display: block;
                        color: #0d2b4a;
                    }
                    .du-shortcut:hover { transform: translateY(-3px); box-shadow: 0 8px 22px rgba(0,0,0,.10); color: #0d47a1; }
                    .du-shortcut i { font-size: 26px; color: #1565c0; margin-bottom: 10px; display: block; }
                    .du-shortcut .label { font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: .4px; }
                </style>

                {{-- Hero --}}
                <div class="du-hero">
                    <div class="greet">Olá,</div>
                    <h1 class="name">{{ $user->name ?? 'Cliente' }}</h1>
                    <div class="sub">Bem-vindo de volta ao seu painel.</div>
                </div>

                {{-- Card do plano atual --}}
                @if ($activeOrder)
                    @php
                        $statusRaw = strtoupper(trim((string) ($activeOrder->getRawOriginal('status') ?? '')));
                        $statusClass = match(true) {
                            in_array($statusRaw, ['ACTIVE', 'ACTIVATED'])      => 'is-active',
                            in_array($statusRaw, ['PENDING', 'INACTIVE'])      => 'is-pending',
                            in_array($statusRaw, ['CANCELED', 'CANCELLED'])    => 'is-canceled',
                            default                                            => 'is-pending',
                        };
                        $statusLabel = match($statusClass) {
                            'is-active'   => 'ATIVO',
                            'is-canceled' => 'CANCELADO',
                            default       => 'PENDENTE',
                        };
                    @endphp
                    <div class="du-plan-card">
                        <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                        <h6><i class="fa fa-id-card mr-1"></i> Seu plano</h6>
                        <div class="plan-name">{{ optional($activeOrder->plan)->name ?? '—' }}</div>
                        <div class="plan-meta">
                            <strong>R$ {{ number_format($activeOrder->value, 2, ',', '.') }}</strong>
                            · {{ $activeOrder->cycle ?? '' }}
                            · próximo vencimento:
                            {{ $activeOrder->next_due_date ? \Carbon\Carbon::parse($activeOrder->next_due_date)->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                @endif

                {{-- 4 atalhos --}}
                <div class="row">
                    <div class="col-6 col-md-3">
                        <a href="javascript:;" class="du-shortcut btn-edit"
                           @if ($customer)
                               data-id="{{ $customer->id }}"
                               data-url="{{ route('panel.customers.edit', $customer->id) }}"
                           @endif
                           data-modal-size="modal-lg">
                            <i class="fa fa-user-edit"></i>
                            <div class="label">Editar dados</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="javascript:;" class="du-shortcut btn-edit"
                           @if ($activeOrder)
                               data-id="{{ $activeOrder->id }}"
                               data-url="{{ route('panel.orders.changePlan', $activeOrder->id) }}"
                           @endif
                           data-modal-size="modal-lg">
                            <i class="fa fa-exchange-alt"></i>
                            <div class="label">Trocar plano</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('panel.orders.index') }}" class="du-shortcut">
                            <i class="fa fa-file-invoice-dollar"></i>
                            <div class="label">Ver faturas</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="javascript:;" class="du-shortcut btn-edit"
                           data-url="{{ config('custom.support_url', '#') }}"
                           data-modal-size="modal-md">
                            <i class="fa fa-life-ring"></i>
                            <div class="label">Suporte</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascriptLocal')
    <script>
        $(function () {
            $(document).on('click', ".btn-edit", function (e) {
                const size = $(this).data('modal-size') || 'modal-lg';
                if (typeof openModal === 'function') {
                    openModal(this, e, size);
                }
            });
        });
    </script>
@endsection

@includeIf("$routeAmbient.$routeCrud.local.index.head")
@includeIf("$routeAmbient.$routeCrud.local.index.javascript")
