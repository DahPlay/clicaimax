@extends("$routeAmbient.template.index")

@section('content')
    <div class="content-wrapper">
        @include("$routeAmbient.$routeCrud.breadcrumb")

        <div class="content">
            <div class="container-fluid">
                @can('admin')
                    <style>
                        :root {
                            --dash-blue-1: #1565c0;
                            --dash-blue-2: #0d47a1;
                            --dash-blue-dark: #0d2b4a;
                        }

                        .dash-filter-card {
                            background: linear-gradient(135deg, var(--dash-blue-1) 0%, var(--dash-blue-2) 100%);
                            color: #fff;
                            border: 0;
                            border-radius: 12px;
                            padding: 16px 20px;
                            margin-bottom: 22px;
                            box-shadow: 0 6px 18px rgba(0,0,0,.08);
                        }
                        .dash-filter-card label {
                            color: rgba(255,255,255,.85);
                            font-weight: 600;
                            font-size: 12px;
                            text-transform: uppercase;
                            letter-spacing: .4px;
                        }
                        .dash-filter-card .form-control,
                        .dash-filter-card select.form-control {
                            border: 0;
                            border-radius: 8px;
                        }
                        .dash-btn-pill { border-radius: 999px; padding: 6px 22px; font-weight: 700; border: 0; }
                        .dash-btn-apply { background: #fff; color: var(--dash-blue-2); }
                        .dash-btn-apply:hover { background: #f5f5f5; color: #082968; }
                        .dash-btn-clear { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.6); }
                        .dash-btn-clear:hover { background: rgba(255,255,255,.12); color: #fff; }

                        /* === KPIs grandes do topo (estilo bandeira) === */
                        .dash-kpi-big {
                            background: #fff;
                            border-radius: 14px;
                            padding: 18px 18px 18px 16px;
                            box-shadow: 0 4px 14px rgba(0,0,0,.06);
                            display: flex;
                            align-items: center;
                            gap: 16px;
                            margin-bottom: 22px;
                            height: 100%;
                        }
                        .dash-kpi-big .kpi-icon-box {
                            width: 58px; height: 58px; border-radius: 12px;
                            display: flex; align-items: center; justify-content: center;
                            color: #fff; font-size: 22px;
                            flex: 0 0 58px;
                        }
                        .dash-kpi-big.is-blue   .kpi-icon-box { background: linear-gradient(135deg, #1565c0, #0d47a1); }
                        .dash-kpi-big.is-amber  .kpi-icon-box { background: linear-gradient(135deg, #fb8c00, #ef6c00); }
                        .dash-kpi-big.is-purple .kpi-icon-box { background: linear-gradient(135deg, #8e24aa, #5e35b1); }
                        .dash-kpi-big.is-teal   .kpi-icon-box { background: linear-gradient(135deg, #26a69a, #00897b); }
                        .dash-kpi-big .kpi-label {
                            font-size: 11.5px; color: #7a8a93;
                            font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
                        }
                        .dash-kpi-big .kpi-value { font-size: 28px; font-weight: 800; color: var(--dash-blue-dark); line-height: 1.05; }
                        .dash-kpi-big .kpi-meta  { font-size: 12px; color: #6c757d; margin-top: 2px; }
                        .dash-kpi-big .kpi-meta .up { color: #2e7d32; font-weight: 600; }

                        /* === KPIs financeiros (bandeira lateral) === */
                        .dash-fin {
                            background: #fff;
                            border-radius: 12px;
                            padding: 14px 16px 14px 18px;
                            box-shadow: 0 4px 14px rgba(0,0,0,.06);
                            position: relative;
                            overflow: hidden;
                            margin-bottom: 22px;
                            height: 100%;
                            border-left: 4px solid #ccc;
                        }
                        .dash-fin.fin-received { border-left-color: #2e7d32; }
                        .dash-fin.fin-confirmed { border-left-color: #1565c0; }
                        .dash-fin.fin-pending   { border-left-color: #f9a825; }
                        .dash-fin.fin-overdue   { border-left-color: #c62828; }
                        .dash-fin.fin-ticket    { border-left-color: #1976d2; }
                        .dash-fin .fin-label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
                        .dash-fin.fin-received .fin-label { color: #2e7d32; }
                        .dash-fin.fin-confirmed .fin-label { color: #1565c0; }
                        .dash-fin.fin-pending .fin-label   { color: #c98610; }
                        .dash-fin.fin-overdue .fin-label   { color: #c62828; }
                        .dash-fin.fin-ticket .fin-label    { color: #1976d2; }
                        .dash-fin .fin-icon { margin-right: 4px; }
                        .dash-fin .fin-value { font-size: 22px; font-weight: 800; color: var(--dash-blue-dark); line-height: 1.1; margin-top: 4px; }
                        .dash-fin .fin-meta  { font-size: 12px; color: #6c757d; }

                        .dash-chart-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 4px 14px rgba(0,0,0,.06); margin-bottom: 22px; }
                        .dash-chart-card h6 {
                            font-weight: 700; color: var(--dash-blue-dark);
                            margin-bottom: 14px;
                            font-size: 13.5px;
                        }
                        .dash-chart-wrap { height: 280px; position: relative; }

                        .dash-section-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 4px 14px rgba(0,0,0,.06); margin-bottom: 22px; }
                        .dash-section-card h6 { font-weight: 700; color: var(--dash-blue-dark); margin-bottom: 12px; font-size: 13.5px; }
                        .dash-link {
                            display: inline-block; padding: 4px 14px; border-radius: 999px;
                            background: linear-gradient(135deg, var(--dash-blue-1), var(--dash-blue-2));
                            color: #fff !important; font-size: 12px; font-weight: 600; text-decoration: none;
                        }
                        .dash-link:hover { opacity: .9; }

                        .dash-section-title { font-size: 12px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: .5px; margin: 6px 0 8px; }
                    </style>

                    {{-- ============ FILTROS ============ --}}
                    <form action="{{ route('panel.main.index') }}" method="GET" class="dash-filter-card">
                        <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                            <i class="fa fa-sliders-h"></i>
                            <strong style="font-size: 14px; letter-spacing: .3px;">Filtros</strong>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-2">
                                <label><i class="fa fa-calendar mr-1"></i>De</label>
                                <input type="date" name="date_from" class="form-control" value="{{ $filters['from'] }}">
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-2">
                                <label><i class="fa fa-calendar mr-1"></i>Até</label>
                                <input type="date" name="date_to" class="form-control" value="{{ $filters['to'] }}">
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-2">
                                <label><i class="fa fa-tags mr-1"></i>Plano</label>
                                <select name="plan_id" class="form-control">
                                    <option value="">Todos os planos</option>
                                    @foreach ($plansOptions as $opt)
                                        <option value="{{ $opt->id }}" @selected($filters['planId'] == $opt->id)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-2">
                                <label><i class="fa fa-ticket-alt mr-1"></i>Cupom</label>
                                <select name="coupon_id" class="form-control">
                                    <option value="">Todos os cupons</option>
                                    @foreach ($couponsOptions as $opt)
                                        <option value="{{ $opt->id }}" @selected($filters['couponId'] == $opt->id)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-1">
                                <label><i class="fa fa-sync-alt mr-1"></i>Ciclo</label>
                                <select name="cycle" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach (['MONTHLY' => 'Mensal', 'QUARTERLY' => 'Trimestral', 'SEMIANNUALLY' => 'Semestral', 'YEARLY' => 'Anual'] as $k => $v)
                                        <option value="{{ $k }}" @selected($filters['cycle'] === $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-1">
                                <label><i class="fa fa-credit-card mr-1"></i>Forma</label>
                                <select name="billing_type" class="form-control">
                                    <option value="">Todas</option>
                                    @foreach (['CREDIT_CARD' => 'Cartão', 'PIX' => 'Pix', 'BOLETO' => 'Boleto'] as $k => $v)
                                        <option value="{{ $k }}" @selected($filters['billingType'] === $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-xl-2 d-flex align-items-end justify-content-end" style="gap: 10px;">
                                <a href="{{ route('panel.main.index') }}" class="dash-btn-pill dash-btn-clear"><i class="fa fa-eraser mr-1"></i>Limpar</a>
                                <button type="submit" class="dash-btn-pill dash-btn-apply"><i class="fa fa-filter mr-1"></i>Aplicar</button>
                            </div>
                        </div>
                    </form>

                    {{-- ============ 4 KPIs DE TOPO ============ --}}
                    <div class="row">
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="dash-kpi-big is-blue">
                                <div class="kpi-icon-box"><i class="fa fa-users"></i></div>
                                <div class="flex-grow-1">
                                    <div class="kpi-label">Clientes (total)</div>
                                    <div class="kpi-value">{{ number_format($customersTotal, 0, ',', '.') }}</div>
                                    <div class="kpi-meta"><span class="up">↑ +{{ $customersInPeriod }}</span> no período</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="dash-kpi-big is-amber">
                                <div class="kpi-icon-box"><i class="fa fa-file-alt"></i></div>
                                <div class="flex-grow-1">
                                    <div class="kpi-label">Assinaturas ativas</div>
                                    <div class="kpi-value">{{ number_format($activeSubscriptions, 0, ',', '.') }}</div>
                                    <div class="kpi-meta">{{ $ordersInPeriod }} no período</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="dash-kpi-big is-purple">
                                <div class="kpi-icon-box"><i class="fa fa-user-cog"></i></div>
                                <div class="flex-grow-1">
                                    <div class="kpi-label">Usuários do sistema</div>
                                    <div class="kpi-value">{{ number_format($systemUsers, 0, ',', '.') }}</div>
                                    <div class="kpi-meta">Inclui admins e clientes</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="dash-kpi-big is-teal">
                                <div class="kpi-icon-box"><i class="fa fa-layer-group"></i></div>
                                <div class="flex-grow-1">
                                    <div class="kpi-label">Planos ativos</div>
                                    <div class="kpi-value">{{ number_format($activePlans, 0, ',', '.') }}</div>
                                    <div class="kpi-meta">Cadastrados na plataforma</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============ KPIs FINANCEIROS (bandeira) ============ --}}
                    <div class="row">
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-fin fin-received">
                                <div class="fin-label"><i class="fa fa-check-circle fin-icon"></i>Recebido</div>
                                <div class="fin-value">R$ {{ number_format($receivedSum, 2, ',', '.') }}</div>
                                <div class="fin-meta">{{ $receivedQty }} cobranças</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-fin fin-confirmed">
                                <div class="fin-label"><i class="fa fa-thumbs-up fin-icon"></i>Confirmado</div>
                                <div class="fin-value">R$ {{ number_format($confirmedSum, 2, ',', '.') }}</div>
                                <div class="fin-meta">{{ $confirmedQty }} cobranças</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-fin fin-pending">
                                <div class="fin-label"><i class="fa fa-hourglass-half fin-icon"></i>Pendente</div>
                                <div class="fin-value">R$ {{ number_format($pendingSum, 2, ',', '.') }}</div>
                                <div class="fin-meta">{{ $pendingQty }} cobranças</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-fin fin-overdue">
                                <div class="fin-label"><i class="fa fa-exclamation-triangle fin-icon"></i>Vencido</div>
                                <div class="fin-value">R$ {{ number_format($overdueSum, 2, ',', '.') }}</div>
                                <div class="fin-meta">{{ $overdueQty }} cobranças</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-fin fin-ticket">
                                <div class="fin-label"><i class="fa fa-receipt fin-icon"></i>Ticket médio</div>
                                <div class="fin-value">R$ {{ number_format($averageTicket, 2, ',', '.') }}</div>
                                <div class="fin-meta">No período</div>
                            </div>
                        </div>
                    </div>

                    {{-- ============ CHARTS PRINCIPAIS (2 colunas) ============ --}}
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-chart-line mr-2"></i>Receita mensal no período</h6>
                                <div class="dash-chart-wrap"><canvas id="chartRevenue"></canvas></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-credit-card mr-2"></i>Forma de pagamento</h6>
                                <div class="dash-chart-wrap"><canvas id="chartBilling"></canvas></div>
                            </div>
                        </div>
                    </div>

                    {{-- ============ CHARTS SECUNDÁRIOS ============ --}}
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-trophy mr-2"></i>Top planos (receita no período)</h6>
                                <div class="dash-chart-wrap"><canvas id="chartTopPlans"></canvas></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-user-plus mr-2"></i>Novos clientes por mês</h6>
                                <div class="dash-chart-wrap"><canvas id="chartCustomers"></canvas></div>
                            </div>
                        </div>
                    </div>

                    {{-- ============ TABELAS AUXILIARES ============ --}}
                    <div class="row">
                        <div class="col-12 col-lg-7">
                            <div class="dash-section-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0"><i class="fa fa-list mr-2"></i>Últimos pedidos</h6>
                                    <a href="{{ route('panel.orders.index') }}" class="dash-link">Ver todas</a>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Plano</th>
                                                <th class="text-right">Valor</th>
                                                <th>Status</th>
                                                <th>Criado em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($lastOrders as $o)
                                                <tr>
                                                    <td>{{ optional($o->customer)->name ?? '-' }}</td>
                                                    <td>{{ optional($o->plan)->name ?? '-' }}</td>
                                                    <td class="text-right">R$ {{ number_format($o->value, 2, ',', '.') }}</td>
                                                    <td><small>{{ $o->payment_status }}</small></td>
                                                    <td><small>{{ optional($o->created_at)->format('d/m/Y H:i') }}</small></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center text-muted">Nenhum pedido no período.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="dash-section-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0"><i class="fa fa-crown mr-2"></i>Top clientes</h6>
                                    <a href="{{ route('panel.customers.index') }}" class="dash-link">Ver todas</a>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th class="text-center">Pedidos</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($topCustomers as $c)
                                                <tr>
                                                    <td>{{ $c->name }}</td>
                                                    <td class="text-center">{{ $c->orders_count }}</td>
                                                    <td class="text-right">R$ {{ number_format($c->total, 2, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="text-center text-muted">Sem clientes no período.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('javascriptLocal')
    {{-- Dados dos charts (PHP -> JS em assignment direto fora de comentario) --}}
    <script>
        window.dashData = {
            revenue: {
                labels:   @json($chartRevenueLabels),
                received: @json($chartReceivedData),
                pending:  @json($chartPendingData),
                overdue:  @json($chartOverdueData),
            },
            topPlans:  { labels: @json($chartTopPlansLabels),  data: @json($chartTopPlansData) },
            billing:   { labels: @json($chartBillingLabels),   data: @json($chartBillingData) },
            customers: { labels: @json($chartCustomersLabels), data: @json($chartCustomersData) }
        };

        $(function () {
            if (typeof Chart === 'undefined') return;

            const blueMain      = '#1565c0';
            const greenReceived = '#2e7d32';
            const amberPending  = '#f9a825';
            const redOverdue    = '#c62828';
            const billingColors = ['#1565c0', '#1976d2', '#42a5f5', '#0d47a1', '#90caf9', '#1e88e5'];
            const moneyFmt = (v) => 'R$ ' + Number(v || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            // 1) Receita mensal — 3 séries (Recebido/Pendente/Vencido)
            new Chart(document.getElementById('chartRevenue').getContext('2d'), {
                type: 'line',
                data: {
                    labels: window.dashData.revenue.labels,
                    datasets: [
                        {
                            label: 'Recebido',
                            data: window.dashData.revenue.received,
                            borderColor: greenReceived,
                            backgroundColor: 'rgba(46,125,50,.15)',
                            fill: true, tension: 0.3, pointRadius: 3, pointBackgroundColor: greenReceived
                        },
                        {
                            label: 'Pendente',
                            data: window.dashData.revenue.pending,
                            borderColor: amberPending,
                            backgroundColor: 'rgba(249,168,37,.10)',
                            fill: false, tension: 0.3, pointRadius: 3, pointBackgroundColor: amberPending
                        },
                        {
                            label: 'Vencido',
                            data: window.dashData.revenue.overdue,
                            borderColor: redOverdue,
                            backgroundColor: 'rgba(198,40,40,.10)',
                            fill: false, tension: 0.3, pointRadius: 3, pointBackgroundColor: redOverdue
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    legend: { position: 'bottom' },
                    tooltips: { mode: 'index', callbacks: { label: (item, data) => data.datasets[item.datasetIndex].label + ': ' + moneyFmt(item.yLabel) } },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, callback: (v) => moneyFmt(v) } }] }
                }
            });

            // 2) Forma de pagamento (donut)
            new Chart(document.getElementById('chartBilling').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: window.dashData.billing.labels,
                    datasets: [{ data: window.dashData.billing.data, backgroundColor: billingColors }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    legend: { position: 'bottom' },
                    cutoutPercentage: 60
                }
            });

            // 3) Top planos (horizontal bar com gradient + label de valor)
            const tpCanvas = document.getElementById('chartTopPlans');
            if (tpCanvas) {
                const tpCtx = tpCanvas.getContext('2d');
                const tpGrad = tpCtx.createLinearGradient(0, 0, tpCanvas.clientWidth || 400, 0);
                tpGrad.addColorStop(0, '#90caf9');
                tpGrad.addColorStop(1, '#0d47a1');

                const valueLabelsPlugin = {
                    afterDatasetsDraw: (chart) => {
                        const ctx = chart.chart.ctx;
                        ctx.save();
                        ctx.font = '600 11px sans-serif';
                        ctx.fillStyle = '#0d2b4a';
                        ctx.textBaseline = 'middle';
                        chart.data.datasets.forEach((ds, i) => {
                            const meta = chart.getDatasetMeta(i);
                            meta.data.forEach((bar, idx) => {
                                const v = ds.data[idx];
                                const pos = bar.tooltipPosition();
                                ctx.textAlign = 'left';
                                ctx.fillText(moneyFmt(v), pos.x + 6, pos.y);
                            });
                        });
                        ctx.restore();
                    }
                };

                new Chart(tpCtx, {
                    type: 'horizontalBar',
                    data: {
                        labels: window.dashData.topPlans.labels,
                        datasets: [{
                            label: 'Receita',
                            data: window.dashData.topPlans.data,
                            backgroundColor: tpGrad,
                            borderColor: '#0d47a1',
                            borderWidth: 0,
                            barThickness: 18
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        legend: { display: false },
                        tooltips: { callbacks: { label: (item) => moneyFmt(item.xLabel) } },
                        scales: {
                            xAxes: [{ ticks: { beginAtZero: true, callback: (v) => moneyFmt(v) } }],
                            yAxes: [{ ticks: { fontSize: 12 } }]
                        },
                        layout: { padding: { right: 80 } }
                    },
                    plugins: [valueLabelsPlugin]
                });
            }

            // 4) Novos clientes por mês
            new Chart(document.getElementById('chartCustomers').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: window.dashData.customers.labels,
                    datasets: [{ label: 'Novos clientes', data: window.dashData.customers.data, backgroundColor: blueMain }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    legend: { display: false },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
                }
            });
        });
    </script>
@endsection

@includeIf("$routeAmbient.$routeCrud.local.index.head")
@includeIf("$routeAmbient.$routeCrud.local.index.javascript")
