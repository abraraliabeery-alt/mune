<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function get(): array
    {
        $cart = session()->get(self::SESSION_KEY);

        if (! is_array($cart)) {
            return [
                'items' => [],
                'table_number' => null,
            ];
        }

        $items = $cart['items'] ?? [];
        $tableNumber = $cart['table_number'] ?? null;

        if (! is_array($items)) {
            $items = [];
        }

        return [
            'items' => $items,
            'table_number' => is_string($tableNumber) ? $tableNumber : null,
        ];
    }

    public function tableNumber(): ?string
    {
        return $this->get()['table_number'];
    }

    public function setTableNumber(?string $tableNumber): void
    {
        $tableNumber = is_string($tableNumber) ? trim($tableNumber) : '';

        session()->put(self::SESSION_KEY, [
            'items' => $this->items(),
            'table_number' => $tableNumber === '' ? null : $tableNumber,
        ]);
    }

    public function items(): array
    {
        return $this->get()['items'];
    }

    public function count(): int
    {
        $count = 0;

        foreach ($this->items() as $item) {
            $qty = (int) ($item['qty'] ?? 0);
            $count += max(0, $qty);
        }

        return $count;
    }

    public function subtotal(): float
    {
        $sum = 0.0;

        foreach ($this->items() as $item) {
            $qty = (int) ($item['qty'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $sum += max(0, $qty) * max(0, $price);
        }

        return $sum;
    }

    public function add(Product $product, int $qty = 1): void
    {
        $qty = max(1, $qty);

        $cart = $this->get();
        $items = $cart['items'];
        $tableNumber = $cart['table_number'] ?? null;

        $id = (string) $product->getKey();

        $existing = $items[$id] ?? null;
        $existingQty = (int) (is_array($existing) ? ($existing['qty'] ?? 0) : 0);

        $items[$id] = [
            'product_id' => (int) $product->getKey(),
            'name' => (string) $product->name,
            'price' => (float) $product->price,
            'image_url' => $product->image_url,
            'qty' => $existingQty + $qty,
        ];

        session()->put(self::SESSION_KEY, [
            'items' => $items,
            'table_number' => $tableNumber,
        ]);
    }

    public function setQty(int $productId, int $qty): void
    {
        $qty = max(0, $qty);

        $cart = $this->get();
        $items = $cart['items'];
        $tableNumber = $cart['table_number'] ?? null;

        $id = (string) $productId;

        if (! array_key_exists($id, $items)) {
            return;
        }

        if ($qty === 0) {
            unset($items[$id]);
        } else {
            $items[$id]['qty'] = $qty;
        }

        session()->put(self::SESSION_KEY, [
            'items' => $items,
            'table_number' => $tableNumber,
        ]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->get();
        $items = $cart['items'];
        $tableNumber = $cart['table_number'] ?? null;

        $id = (string) $productId;

        if (! array_key_exists($id, $items)) {
            return;
        }

        unset($items[$id]);

        session()->put(self::SESSION_KEY, [
            'items' => $items,
            'table_number' => $tableNumber,
        ]);
    }

    public function clear(): void
    {
        session()->put(self::SESSION_KEY, [
            'items' => [],
            'table_number' => $this->tableNumber(),
        ]);
    }
}
