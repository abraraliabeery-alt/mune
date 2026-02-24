<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $query = Order::query()->withCount('items')->orderByDesc('id');

        if ($status !== '') {
            $query->where('status', $status);
        }

        return view('admin.orders.index', [
            'orders' => $query->paginate(20),
            'status' => $status,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,preparing,ready,delivered,cancelled'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.orders.show', ['order' => $order->id]);
    }
}
