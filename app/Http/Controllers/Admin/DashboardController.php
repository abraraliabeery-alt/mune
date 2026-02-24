<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $countsByStatus = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        $total = (int) array_sum($countsByStatus);

        return view('admin.dashboard', [
            'totalOrders' => $total,
            'countsByStatus' => $countsByStatus,
        ]);
    }
}
