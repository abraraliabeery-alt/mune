<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $countsByStatus = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        $totalOrders = (int) array_sum($countsByStatus);

        $today = Carbon::today();

        $todayOrders = (int) Order::query()
            ->whereDate('created_at', $today)
            ->count();

        $todayRevenue = (float) Order::query()
            ->whereDate('created_at', $today)
            ->sum('subtotal');

        $totalRevenue = (float) Order::query()->sum('subtotal');

        $productsTotal = (int) Product::query()->count();
        $productsAvailable = (int) Product::query()->where('is_available', true)->count();
        $productsUnavailable = $productsTotal - $productsAvailable;

        $recentOrders = Order::query()
            ->withCount('items')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'countsByStatus' => $countsByStatus,
            'todayOrders' => $todayOrders,
            'todayRevenue' => $todayRevenue,
            'totalRevenue' => $totalRevenue,
            'productsTotal' => $productsTotal,
            'productsAvailable' => $productsAvailable,
            'productsUnavailable' => $productsUnavailable,
            'recentOrders' => $recentOrders,
        ]);
    }
}
