<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PageView;
use App\Models\Photo;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'photos' => Photo::count(),
            'published' => Photo::where('is_published', true)->count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue' => Order::whereIn('status', ['paid', 'shipped', 'completed'])->sum('total'),
        ];

        $latestOrders = Order::with('items')->latest()->take(5)->get();

        // ===== Analytics =====
        $today = CarbonImmutable::now()->startOfDay();
        $startMonth = $today->subDays(29); // últimos 30 días
        $startTwoWeeks = $today->subDays(13); // últimos 14 días para chart

        $analytics = [
            'today' => PageView::where('created_at', '>=', $today)->count(),
            'last_7d' => PageView::where('created_at', '>=', $today->subDays(6))->count(),
            'last_30d' => PageView::where('created_at', '>=', $startMonth)->count(),
            'unique_30d' => PageView::where('created_at', '>=', $startMonth)->distinct('session_id')->count('session_id'),
            'total' => PageView::count(),
        ];

        // Serie diaria (14 días)
        $rows = PageView::selectRaw('DATE(created_at) AS d, COUNT(*) AS v, COUNT(DISTINCT session_id) AS u')
            ->where('created_at', '>=', $startTwoWeeks)
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy(fn ($r) => $r->d);

        $chart = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = $today->subDays($i);
            $key = $day->format('Y-m-d');
            $chart[] = [
                'date' => $key,
                'label' => $day->translatedFormat('d M'),
                'visits' => (int) ($rows[$key]->v ?? 0),
                'unique' => (int) ($rows[$key]->u ?? 0),
            ];
        }
        $chartMax = max(1, max(array_column($chart, 'visits')));

        // Top páginas (últimos 30 días)
        $topPages = PageView::selectRaw('path, COUNT(*) AS visits, COUNT(DISTINCT session_id) AS uniques')
            ->where('created_at', '>=', $startMonth)
            ->groupBy('path')
            ->orderByDesc('visits')
            ->take(10)
            ->get();

        // Top referers
        $topReferers = PageView::selectRaw('referer, COUNT(*) AS visits')
            ->where('created_at', '>=', $startMonth)
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->groupBy('referer')
            ->orderByDesc('visits')
            ->take(5)
            ->get();

        // Visitas recientes (últimas 10)
        $recentVisits = PageView::with('user')->latest('created_at')->take(10)->get();

        return view('admin.dashboard', compact(
            'stats', 'latestOrders',
            'analytics', 'chart', 'chartMax', 'topPages', 'topReferers', 'recentVisits'
        ));
    }
}
