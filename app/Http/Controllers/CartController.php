<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    private function cartResponse(Request $request, CartService $cart): JsonResponse
    {
        return response()->json([
            'cartCount' => $cart->count(),
            'subtotal' => $cart->subtotal(),
            'count' => $cart->count(),
            'table_number' => $cart->tableNumber(),
            'html' => view('cart._content', [
                'items' => $cart->items(),
                'subtotal' => $cart->subtotal(),
                'count' => $cart->count(),
                'tableNumber' => $cart->tableNumber(),
            ])->render(),
        ]);
    }

    public function index(CartService $cart)
    {
        return view('cart.index', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'count' => $cart->count(),
            'tableNumber' => $cart->tableNumber(),
        ]);
    }

    public function setTableNumber(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'table_number' => ['nullable', 'string', 'max:20'],
        ]);

        $cart->setTableNumber($validated['table_number'] ?? null);

        if ($request->expectsJson()) {
            return $this->cartResponse($request, $cart);
        }

        return redirect()->route('cart.index');
    }

    public function add(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::query()
            ->whereKey($validated['product_id'])
            ->where('is_available', true)
            ->firstOrFail();

        $cart->add($product, (int) ($validated['qty'] ?? 1));

        if ($request->expectsJson()) {
            return $this->cartResponse($request, $cart);
        }

        return redirect()->back()->with('status', 'cart_added');
    }

    public function update(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->setQty((int) $validated['product_id'], (int) $validated['qty']);

        if ($request->expectsJson()) {
            return $this->cartResponse($request, $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart->remove((int) $validated['product_id']);

        if ($request->expectsJson()) {
            return $this->cartResponse($request, $cart);
        }

        return redirect()->route('cart.index');
    }

    public function clear(CartService $cart)
    {
        $cart->clear();

        if (request()->expectsJson()) {
            return $this->cartResponse(request(), $cart);
        }

        return redirect()->route('cart.index');
    }
}
