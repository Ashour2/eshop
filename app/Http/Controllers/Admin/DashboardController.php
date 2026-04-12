<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User, OrderItem};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── إحصائيات سريعة ───────────────────────────────
        $stats = [
            'products'       => Product::count(),
            'orders'         => Order::count(),
            'revenue'        => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'customers'      => User::where('is_admin', false)->count(),
            'today_orders'   => Order::whereDate('created_at', today())->count(),
            'today_revenue'  => Order::whereDate('created_at', today())
                                     ->where('status', '!=', 'cancelled')
                                     ->sum('total'),
        ];

        // ── آخر 5 طلبات ──────────────────────────────────
        $recent_orders = Order::with('user')->latest()->take(5)->get();

        // ── Chart 1: مبيعات آخر 30 يوم ───────────────────
        $salesData = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(29))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $salesLabels  = [];
        $salesTotals  = [];
        $salesCounts  = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $salesLabels[] = now()->subDays($i)->format('m/d');
            $salesTotals[] = $salesData->get($date)?->total ?? 0;
            $salesCounts[] = $salesData->get($date)?->count ?? 0;
        }

        // ── Chart 2: أكثر 7 منتجات مبيعاً ────────────────
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(7)
            ->get();

        $topProductLabels = $topProducts->pluck('product_name')->toArray();
        $topProductData   = $topProducts->pluck('total_sold')->toArray();

        // ── Chart 3: توزيع حالات الطلبات ─────────────────
        $statusData = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statusLabels = ['قيد الانتظار', 'قيد المعالجة', 'تم الشحن', 'تم التسليم', 'ملغي'];
        $statusKeys   = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusCounts = array_map(fn($k) => $statusData->get($k)?->count ?? 0, $statusKeys);
        $statusColors = ['#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#dc3545'];

        // ── Chart 4: المبيعات الشهرية (آخر 6 أشهر) ───────
        $monthlyData = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT));

        $monthlyLabels = [];
        $monthlyTotals = [];
        $arabicMonths  = ['','يناير','فبراير','مارس','أبريل','مايو','يونيو',
                           'يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];

        for ($i = 5; $i >= 0; $i--) {
            $d   = now()->subMonths($i);
            $key = $d->format('Y-m');
            $monthlyLabels[] = $arabicMonths[(int)$d->format('n')] . ' ' . $d->format('Y');
            $monthlyTotals[] = round($monthlyData->get($key)?->total ?? 0, 2);
        }

        return view('admin.dashboard', compact(
            'stats', 'recent_orders',
            'salesLabels', 'salesTotals', 'salesCounts',
            'topProductLabels', 'topProductData',
            'statusLabels', 'statusCounts', 'statusColors',
            'monthlyLabels', 'monthlyTotals'
        ));
    }
}
