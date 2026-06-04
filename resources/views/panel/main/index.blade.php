@extends("$routeAmbient.template.index")

@section('content')
    <div class="content-wrapper">
        @include("$routeAmbient.$routeCrud.breadcrumb")

        <div class="content">
            <div class="container-fluid">
                @can('admin')
                    <style>
                        .dash-filter-card {
                            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
                            color: #fff;
                            border: 0;
                            border-radius: 12px;
                            padding: 18px 20px;
                            margin-bottom: 22px;
                            box-shadow: 0 6px 18px rgba(0,0,0,.08);
                        }
                        .dash-filter-card label { color: #d7e6c9; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; }
                        .dash-filter-card .form-control, .dash-filter-card select.form-control { border: 0; border-radius: 8px; }
                        .dash-btn-pill { border-radius: 999px; padding: 6px 22px; font-weight: 700; border: 0; }
                        .dash-btn-apply { background: #fff; color: #1b5e20; }
                        .dash-btn-apply:hover { background: #f5f5f5; color: #154a18; }
                        .dash-btn-clear { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.6); }
                        .dash-btn-clear:hover { background: rgba(255,255,255,.12); color: #fff; }

                        .dash-kpi { border: 0; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,.06); overflow: hidden; }
                        .dash-kpi .kpi-label { font-size: 12px; color: #7a8a93; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
                        .dash-kpi .kpi-value { font-size: 22px; font-weight: 800; color: #2c3e0f; line-height: 1.1; margin: 4px 0 2px; }
                        .dash-kpi .kpi-meta  { font-size: 12px; color: #6c757d; }
                        .dash-kpi .kpi-icon  { float: right; font-size: 26px; opacity: .25; color: #1b5e20; }

                        .dash-chart-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 4px 14px rgba(0,0,0,.06); margin-bottom: 22px; }
                        .dash-chart-card h6 { font-weight: 700; color: #2c3e0f; margin-bottom: 14px; text-transform: uppercase; font-size: 13px; letter-spacing: .4px; }
                        .dash-chart-wrap { height: 280px; position: relative; }

                        .dash-section-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 4px 14px rgba(0,0,0,.06); margin-bottom: 22px; }
                        .dash-section-card h6 { font-weight: 700; color: #2c3e0f; margin-bottom: 12px; text-transform: uppercase; font-size: 13px; letter-spacing: .4px; }
                        .dash-link { display: inline-block; padding: 4px 14px; border-radius: 999px; background: linear-gradient(135deg, #2e7d32, #1b5e20); color: #fff !important; font-size: 12px; font-weight: 600; text-decoration: none; }
                        .dash-link:hover { opacity: .9; }
                    </style>

                    {{-- ============ FILTROS ============ --}}
                    <form action="{{ route('panel.main.index') }}" method="GET" class="dash-filter-card">
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-3">
                                <label for="date_from">De</label>
                                <input type="date" name="date_from" id="date_from" class="form-control"
                                       value="{{ $filters['from'] }}">
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3">
                                <label for="date_to">Até</label>
                                <input type="date" name="date_to" id="date_to" class="form-control"
                                       value="{{ $filters['to'] }}">
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3">
                                <label for="plan_id">Plano</label>
                                <select name="plan_id" id="plan_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($plansOptions as $opt)
                                        <option value="{{ $opt->id }}" @selected($filters['planId'] == $opt->id)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3">
                                <label for="coupon_id">Cupom</label>
                                <select name="coupon_id" id="coupon_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($couponsOptions as $opt)
                                        <option value="{{ $opt->id }}" @selected($filters['couponId'] == $opt->id)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3">
                                <label for="cycle">Ciclo</label>
                                <select name="cycle" id="cycle" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach (['MONTHLY' => 'Mensal', 'QUARTERLY' => 'Trimestral', 'SEMIANNUALLY' => 'Semestral', 'YEARLY' => 'Anual'] as $k => $v)
                                        <option value="{{ $k }}" @selected($filters['cycle'] === $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3">
                                <label for="billing_type">Forma de Pagamento</label>
                                <select name="billing_type" id="billing_type" class="form-control">
                                    <option value="">Todas</option>
                                    @foreach (['CREDIT_CARD' => 'Cartão', 'PIX' => 'Pix', 'BOLETO' => 'Boleto'] as $k => $v)
                                        <option value="{{ $k }}" @selected($filters['billingType'] === $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 d-flex align-items-end justify-content-end" style="gap: 10px;">
                                <a href="{{ route('panel.main.index') }}" class="dash-btn-pill dash-btn-clear">Limpar</a>
                                <button type="submit" class="dash-btn-pill dash-btn-apply">Aplicar</button>
                            </div>
                        </div>
                    </form>

                    {{-- ============ KPIs FINANCEIROS ============ --}}
                    <div class="row">
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-check-circle kpi-icon"></i>
                                <div class="kpi-label">Recebido</div>
                                <div class="kpi-value">R$ {{ number_format($receivedSum, 2, ',', '.') }}</div>
                                <div class="kpi-meta">{{ $receivedQty }} pagamento(s)</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-thumbs-up kpi-icon"></i>
                                <div class="kpi-label">Confirmado</div>
                                <div class="kpi-value">R$ {{ number_format($confirmedSum, 2, ',', '.') }}</div>
                                <div class="kpi-meta">{{ $confirmedQty }} pagamento(s)</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-hourglass-half kpi-icon"></i>
                                <div class="kpi-label">Pendente</div>
                                <div class="kpi-value">R$ {{ number_format($pendingSum, 2, ',', '.') }}</div>
                                <div class="kpi-meta">{{ $pendingQty }} pagamento(s)</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-exclamation-triangle kpi-icon"></i>
                                <div class="kpi-label">Vencido</div>
                                <div class="kpi-value">R$ {{ number_format($overdueSum, 2, ',', '.') }}</div>
                                <div class="kpi-meta">{{ $overdueQty }} pagamento(s)</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-receipt kpi-icon"></i>
                                <div class="kpi-label">Ticket Médio</div>
                                <div class="kpi-value">R$ {{ number_format($averageTicket, 2, ',', '.') }}</div>
                                <div class="kpi-meta">média por pagamento</div>
                            </div>
                        </div>
                    </div>

                    {{-- ============ AUDIT (Usuários / Clientes / Assinaturas) ============ --}}
                    <div class="row mt-3">
                        <div class="col-12 col-md-4">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-user kpi-icon"></i>
                                <div class="kpi-label">Usuários no período</div>
                                <div class="kpi-value">{{ $usersInPeriod }}</div>
                                <div class="kpi-meta">Total Geral: {{ $usersTotal }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-users kpi-icon"></i>
                                <div class="kpi-label">Clientes no período</div>
                                <div class="kpi-value">{{ $customersInPeriod }}</div>
                                <div class="kpi-meta">Total Geral: {{ $customersTotal }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="dash-kpi p-3 bg-white">
                                <i class="fa fa-file-alt kpi-icon"></i>
                                <div class="kpi-label">Assinaturas no período</div>
                                <div class="kpi-value">{{ $ordersInPeriod }}</div>
                                <div class="kpi-meta">com filtros aplicados</div>
                            </div>
                        </div>
                    </div>

                    {{-- ============ CHARTS ============ --}}
                    <div class="row mt-4">
                        <div class="col-12 col-lg-6">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-chart-line mr-2"></i>Receita mensal (últimos 12 meses)</h6>
                                <div class="dash-chart-wrap"><canvas id="chartRevenue"></canvas></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-trophy mr-2"></i>Top planos (receita no período)</h6>
                                <div class="dash-chart-wrap"><canvas id="chartTopPlans"></canvas></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="dash-chart-card">
                                <h6><i class="fa fa-credit-card mr-2"></i>Distribuição por forma de pagamento</h6>
                                <div class="dash-chart-wrap"><canvas id="chartBilling"></canvas></div>
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
            revenue:   { labels: @json($chartRevenueLabels),   data: @json($chartRevenueData) },
            topPlans:  { labels: @json($chartTopPlansLabels),  data: @json($chartTopPlansData) },
            billing:   { labels: @json($chartBillingLabels),   data: @json($chartBillingData) },
            customers: { labels: @json($chartCustomersLabels), data: @json($chartCustomersData) }
        };

        $(function () {
            if (typeof Chart === 'undefined') return;

            const greenSolid    = '#2e7d32';
            const greenSoft     = 'rgba(46,125,50,.18)';
            const billingColors = ['#2e7d32', '#43a047', '#a5d6a7', '#1b5e20', '#66bb6a', '#388e3c'];
            const moneyFmt = (v) => 'R$ ' + Number(v || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            // 1) Receita mensal
            new Chart(document.getElementById('chartRevenue').getContext('2d'), {
                type: 'line',
                data: {
                    labels: window.dashData.revenue.labels,
                    datasets: [{
                        label: 'Receita',
                        data: window.dashData.revenue.data,
                        borderColor: greenSolid,
                        backgroundColor: greenSoft,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: greenSolid
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: { callbacks: { label: (item) => moneyFmt(item.yLabel) } },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, callback: (v) => moneyFmt(v) } }] }
                }
            });

            // 2) Top planos (barras horizontais, gradient + label de valor)
            const tpCanvas = document.getElementById('chartTopPlans');
            const tpCtx = tpCanvas.getContext('2d');
            const tpGrad = tpCtx.createLinearGradient(0, 0, tpCanvas.clientWidth || 400, 0);
            tpGrad.addColorStop(0, '#a5d6a7');
            tpGrad.addColorStop(1, '#1b5e20');

            const valueLabelsPlugin = {
                afterDatasetsDraw: (chart) => {
                    const ctx = chart.chart.ctx;
                    ctx.save();
                    ctx.font = '600 11px sans-serif';
                    ctx.fillStyle = '#2c3e0f';
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
                        borderColor: greenSolid,
                        borderWidth: 0,
                        barThickness: 18
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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

            // 3) Distribuição por billing_type (donut)
            new Chart(document.getElementById('chartBilling').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: window.dashData.billing.labels,
                    datasets: [{
                        data: window.dashData.billing.data,
                        backgroundColor: billingColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { position: 'right' },
                    cutoutPercentage: 60
                }
            });

            // 4) Novos clientes por mês
            new Chart(document.getElementById('chartCustomers').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: window.dashData.customers.labels,
                    datasets: [{
                        label: 'Novos clientes',
                        data: window.dashData.customers.data,
                        backgroundColor: greenSolid,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
                }
            });
        });
    </script>
@endsection

@includeIf("$routeAmbient.$routeCrud.local.index.head")
@includeIf("$routeAmbient.$routeCrud.local.index.javascript")
