<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyAccount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request, CartService $cart)
    {
        $items = $cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validate([
            'order_type' => ['required', 'in:table,delivery'],
            'customer_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'loyalty_phone' => ['nullable', 'string', 'max:30'],
            'location_url' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $tableNumber = $cart->tableNumber();

        $loyaltyPhone = trim((string) ($validated['loyalty_phone'] ?? ''));
        if ($loyaltyPhone === '') {
            $loyaltyPhone = null;
        }

        if (($validated['order_type'] ?? null) === 'table') {
            if (! $tableNumber) {
                return back()->withErrors(['table_number' => __('messages.orders_table_required')]);
            }
        } else {
            $name = trim((string) ($validated['customer_name'] ?? ''));
            $phone = trim((string) ($validated['phone'] ?? ''));
            $address = trim((string) ($validated['location_url'] ?? ''));

            if ($name === '') {
                return back()->withErrors(['customer_name' => __('messages.orders_delivery_name_required')])->withInput();
            }

            if ($phone === '') {
                return back()->withErrors(['phone' => __('messages.orders_delivery_phone_required')])->withInput();
            }

            if ($address === '') {
                return back()->withErrors(['location_url' => __('messages.orders_delivery_address_required')])->withInput();
            }

            $validated['customer_name'] = $name;
            $validated['phone'] = $phone;
            $validated['location_url'] = $address;

            if (! $loyaltyPhone) {
                $loyaltyPhone = $phone;
            }
        }

        $subtotal = $cart->subtotal();

        $order = DB::transaction(function () use ($validated, $items, $subtotal, $tableNumber, $loyaltyPhone, $request) {
            $publicCode = $this->generatePublicCode();

            $isDelivery = ($validated['order_type'] ?? null) === 'delivery';

            $earnedPoints = 0;
            $loyaltyPhoneToStore = null;
            if ($loyaltyPhone) {
                $loyaltyPhoneToStore = $loyaltyPhone;
                $earnedPoints = (int) floor(((float) $subtotal) / 10);

                if ($earnedPoints > 0) {
                    $account = LoyaltyAccount::query()->firstOrCreate(
                        ['phone' => $loyaltyPhoneToStore],
                        ['points' => 0]
                    );

                    $account->update([
                        'points' => ((int) $account->points) + $earnedPoints,
                    ]);
                }
            }

            /** @var Order $order */
            $order = Order::query()->create([
                'customer_name' => $isDelivery ? (string) ($validated['customer_name'] ?? '') : ('Table '.$tableNumber),
                'phone' => $isDelivery ? (string) ($validated['phone'] ?? '') : '-',
                'delivery_method' => $isDelivery ? 'delivery' : 'pickup',
                'location_url' => $isDelivery ? (string) ($validated['location_url'] ?? '') : null,
                'table_number' => $isDelivery ? null : $tableNumber,
                'public_code' => $publicCode,
                'loyalty_phone' => $loyaltyPhoneToStore,
                'loyalty_points' => $earnedPoints,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'status' => 'new',
                'created_by_user_id' => $request->user()?->id,
                'updated_by_user_id' => $request->user()?->id,
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

        return redirect()->route('orders.show', ['publicCode' => $order->public_code]);
    }

    public function show(string $publicCode)
    {
        $order = Order::query()
            ->with('items')
            ->where('public_code', $publicCode)
            ->firstOrFail();

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    private function generatePublicCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Order::query()->where('public_code', $code)->exists());

        return $code;
    }
}
