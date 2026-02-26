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
        $q = trim($request->string('q')->toString());
        $type = $request->string('type')->toString();

        $query = Order::query()->withCount('items')->orderByDesc('id');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($type === 'delivery') {
            $query->where('delivery_method', 'delivery');
        } elseif ($type === 'table') {
            $query->where('delivery_method', '!=', 'delivery');
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('public_code', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        return view('admin.orders.index', [
            'orders' => $query->paginate(20),
            'status' => $status,
            'q' => $q,
            'type' => $type,
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
