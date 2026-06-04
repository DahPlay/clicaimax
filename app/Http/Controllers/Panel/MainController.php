<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        // === Filtros (default = ano atual) ===
        $from = $this->request->get('date_from') ?: Carbon::now()->startOfYear()->toDateString();
        $to   = $this->request->get('date_to')   ?: Carbon::now()->endOfYear()->toDateString();
        $planId       = $this->request->get('plan_id');
        $couponId     = $this->request->get('coupon_id');
        $cycle        = $this->request->get('cycle');
        $billingType  = $this->request->get('billing_type');

        $applyFilters = function ($query) use ($from, $to, $planId, $couponId, $cycle, $billingType) {
            $query->whereDate('orders.created_at', '>=', $from)
                  ->whereDate('orders.created_at', '<=', $to);

            if ($planId)      $query->where('orders.plan_id', $planId);
            if ($cycle)       $query->where('orders.cycle', $cycle);
            if ($billingType) $query->where('orders.billing_type', $billingType);
            if ($couponId) {
                $query->whereExists(function ($q) use ($couponId) {
                    $q->select(DB::raw(1))
                      ->from('customers')
                      ->whereColumn('customers.id', 'orders.customer_id')
                      ->where('customers.coupon_id', $couponId);
                });
            }
        };

        // === KPIs financeiros — UM SELECT agregado com UPPER+TRIM (sem REFUNDED) ===
        $financials = Order::query()
            ->selectRaw('
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "RECEIVED"  THEN value ELSE 0 END) as received_sum,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "CONFIRMED" THEN value ELSE 0 END) as confirmed_sum,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "PENDING"   THEN value ELSE 0 END) as pending_sum,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "OVERDUE"   THEN value ELSE 0 END) as overdue_sum,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "RECEIVED"  THEN 1 ELSE 0 END) as received_qty,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "CONFIRMED" THEN 1 ELSE 0 END) as confirmed_qty,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "PENDING"   THEN 1 ELSE 0 END) as pending_qty,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "OVERDUE"   THEN 1 ELSE 0 END) as overdue_qty
            ')
            ->tap($applyFilters)
            ->first();

        $receivedSum   = (float) ($financials->received_sum   ?? 0);
        $receivedQty   = (int)   ($financials->received_qty   ?? 0);
        $confirmedSum  = (float) ($financials->confirmed_sum  ?? 0);
        $confirmedQty  = (int)   ($financials->confirmed_qty  ?? 0);
        $pendingSum    = (float) ($financials->pending_sum    ?? 0);
        $pendingQty    = (int)   ($financials->pending_qty    ?? 0);
        $overdueSum    = (float) ($financials->overdue_sum    ?? 0);
        $overdueQty    = (int)   ($financials->overdue_qty    ?? 0);
        $averageTicket = $receivedQty > 0 ? $receivedSum / $receivedQty : 0;

        // === Audit de usuários ===
        $usersInPeriod = User::whereDate('created_at', '>=', $from)
                             ->whereDate('created_at', '<=', $to)
                             ->count();
        $usersTotal = User::count();

        $customersInPeriod = Customer::whereDate('created_at', '>=', $from)
                                     ->whereDate('created_at', '<=', $to)
                                     ->count();
        $customersTotal = Customer::count();

        $ordersInPeriod = Order::query()->tap($applyFilters)->count();

        // KPIs principais do topo (4 cards)
        $activeSubscriptions = Order::query()
            ->whereRaw('UPPER(TRIM(COALESCE(status, ""))) IN ("ACTIVE","ACTIVATED")')
            ->count();
        $systemUsers = $usersTotal;
        $activePlans = Plan::where('is_active', 1)->count();

        // === Charts ===
        // 1) Receita mensal por status (Recebido / Pendente / Vencido) — usa o período do filtro.
        $monthlyAgg = Order::query()
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as ym,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "RECEIVED" THEN value ELSE 0 END) as received,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "PENDING"  THEN value ELSE 0 END) as pending,
                SUM(CASE WHEN UPPER(TRIM(payment_status)) = "OVERDUE"  THEN value ELSE 0 END) as overdue
            ')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $fromC = Carbon::parse($from)->startOfMonth();
        $toC   = Carbon::parse($to)->startOfMonth();
        $monthsCount = max(1, $fromC->diffInMonths($toC) + 1);

        $chartRevenueLabels   = [];
        $chartReceivedData    = [];
        $chartPendingData     = [];
        $chartOverdueData     = [];
        for ($i = 0; $i < $monthsCount; $i++) {
            $m   = $fromC->copy()->addMonths($i);
            $key = $m->format('Y-m');
            $row = $monthlyAgg[$key] ?? null;
            $chartRevenueLabels[] = $m->translatedFormat('M/y');
            $chartReceivedData[]  = (float) ($row->received ?? 0);
            $chartPendingData[]   = (float) ($row->pending  ?? 0);
            $chartOverdueData[]   = (float) ($row->overdue  ?? 0);
        }

        // 2) Top planos (por receita RECEIVED no período do filtro)
        $topPlans = Order::query()
            ->selectRaw('plans.name as plan_name, SUM(orders.value) as total')
            ->join('plans', 'plans.id', '=', 'orders.plan_id')
            ->whereRaw('UPPER(TRIM(orders.payment_status)) = "RECEIVED"')
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->groupBy('plans.id', 'plans.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $chartTopPlansLabels = $topPlans->pluck('plan_name')->toArray();
        $chartTopPlansData   = $topPlans->pluck('total')->map(fn($v) => (float) $v)->toArray();

        // 3) Distribuição por billing_type (donut)
        $byBillingType = Order::query()
            ->selectRaw('billing_type, COUNT(*) as qty')
            ->tap($applyFilters)
            ->groupBy('billing_type')
            ->pluck('qty', 'billing_type');

        $chartBillingLabels = [];
        $chartBillingData   = [];
        foreach ($byBillingType as $type => $qty) {
            $chartBillingLabels[] = $type ?: 'N/A';
            $chartBillingData[]   = (int) $qty;
        }

        // 4) Novos clientes por mês — usa o mesmo período do filtro (mesmos labels do chart de receita).
        $newCustomers = Customer::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as qty')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('qty', 'ym');

        $chartCustomersLabels = $chartRevenueLabels;
        $chartCustomersData   = [];
        for ($i = 0; $i < $monthsCount; $i++) {
            $m = $fromC->copy()->addMonths($i);
            $chartCustomersData[] = (int) ($newCustomers[$m->format('Y-m')] ?? 0);
        }

        // === Listas auxiliares ===
        $lastOrders = Order::query()
            ->with(['customer:id,name', 'plan:id,name'])
            ->tap($applyFilters)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $topCustomers = Order::query()
            ->selectRaw('customers.id, customers.name, COUNT(orders.id) as orders_count, SUM(orders.value) as total')
            ->join('customers', 'customers.id', '=', 'orders.customer_id')
            ->whereRaw('UPPER(TRIM(orders.payment_status)) = "RECEIVED"')
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Opções de filtro
        $plansOptions   = Plan::orderBy('name')->get(['id', 'name']);
        $couponsOptions = Coupon::orderBy('name')->get(['id', 'name']);

        $filters = compact('from', 'to', 'planId', 'couponId', 'cycle', 'billingType');

        return view('panel.main.index', [
            'filters' => $filters,
            'plansOptions' => $plansOptions,
            'couponsOptions' => $couponsOptions,

            // KPIs financeiros
            'receivedSum'   => $receivedSum,
            'receivedQty'   => $receivedQty,
            'confirmedSum'  => $confirmedSum,
            'confirmedQty'  => $confirmedQty,
            'pendingSum'    => $pendingSum,
            'pendingQty'    => $pendingQty,
            'overdueSum'    => $overdueSum,
            'overdueQty'    => $overdueQty,
            'averageTicket' => $averageTicket,

            // Audit
            'usersInPeriod'     => $usersInPeriod,
            'usersTotal'        => $usersTotal,
            'customersInPeriod' => $customersInPeriod,
            'customersTotal'    => $customersTotal,
            'ordersInPeriod'    => $ordersInPeriod,

            // KPIs principais do topo (4 cards estilo bandeira)
            'activeSubscriptions' => $activeSubscriptions,
            'systemUsers'         => $systemUsers,
            'activePlans'         => $activePlans,

            // Charts
            'chartRevenueLabels'   => $chartRevenueLabels,
            'chartReceivedData'    => $chartReceivedData,
            'chartPendingData'     => $chartPendingData,
            'chartOverdueData'     => $chartOverdueData,
            'chartTopPlansLabels'  => $chartTopPlansLabels,
            'chartTopPlansData'    => $chartTopPlansData,
            'chartBillingLabels'   => $chartBillingLabels,
            'chartBillingData'     => $chartBillingData,
            'chartCustomersLabels' => $chartCustomersLabels,
            'chartCustomersData'   => $chartCustomersData,

            // Listas auxiliares
            'lastOrders'   => $lastOrders,
            'topCustomers' => $topCustomers,
        ]);
    }

    public function indexUser()
    {
        $user = auth()->user();
        $customer = Customer::where('user_id', $user->id)->first();

        $activeOrder = $customer
            ? Order::with('plan')
                ->where('customer_id', $customer->id)
                ->orderByDesc('id')
                ->first()
            : null;

        return view('panel.main.index-user', compact('user', 'customer', 'activeOrder'));
    }
}
