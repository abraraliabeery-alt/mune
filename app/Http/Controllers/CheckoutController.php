<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(CartService $cart)
    {
        $items = $cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        return view('checkout.create', [
            'items' => $items,
            'subtotal' => $cart->subtotal(),
            'count' => $cart->count(),
        ]);
    }

    public function store(Request $request, CartService $cart)
    {
        $items = $cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'delivery_method' => ['required', 'in:pickup,delivery'],
            'location_url' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (($validated['delivery_method'] ?? null) === 'delivery') {
            $location = trim((string) ($validated['location_url'] ?? ''));

            if ($location === '') {
                return back()
                    ->withErrors(['location_url' => __('messages.checkout_location_required')])
                    ->withInput();
            }

            if (! filter_var($location, FILTER_VALIDATE_URL)) {
                return back()
                    ->withErrors(['location_url' => __('messages.checkout_location_invalid')])
                    ->withInput();
            }

            $validated['location_url'] = $location;
        } else {
            $validated['location_url'] = null;
        }

        $subtotal = $cart->subtotal();

        $order = DB::transaction(function () use ($validated, $items, $subtotal) {
            /** @var Order $order */
            $order = Order::query()->create([
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'delivery_method' => $validated['delivery_method'],
                'location_url' => $validated['location_url'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'status' => 'new',
            ]);

            foreach ($items as $item) {
                $qty = max(1, (int) ($item['qty'] ?? 1));
                $unit = (float) ($item['price'] ?? 0);
                $line = $qty * $unit;

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => (int) ($item['product_id'] ?? 0) ?: null,
                    'product_name' => (string) ($item['name'] ?? ''),
                    'unit_price' => $unit,
                    'qty' => $qty,
                    'line_total' => $line,
                ]);
            }

            return $order;
        });

        $cart->clear();

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }

    public function success(int $order)
    {
        $orderModel = Order::query()->with('items')->findOrFail($order);

        return view('checkout.success', [
            'order' => $orderModel,
        ]);
    }
}
