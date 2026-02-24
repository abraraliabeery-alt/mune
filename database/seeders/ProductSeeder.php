<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        Product::create([
            'name' => 'Espresso',
            'category' => 'Hot',
            'price' => 10.00,
            'description' => 'Strong and rich espresso shot.',
            'image_url' => null,
            'is_available' => true,
        ]);

        Product::create([
            'name' => 'Cappuccino',
            'category' => 'Hot',
            'price' => 14.00,
            'description' => 'Espresso with steamed milk and foam.',
            'image_url' => null,
            'is_available' => true,
        ]);

        Product::create([
            'name' => 'Iced Latte',
            'category' => 'Iced',
            'price' => 16.00,
            'description' => 'Chilled espresso with milk and ice.',
            'image_url' => null,
            'is_available' => true,
        ]);

        Product::create([
            'name' => 'Chocolate Brownie',
            'category' => 'Desserts',
            'price' => 12.00,
            'description' => 'Soft brownie with chocolate chunks.',
            'image_url' => null,
            'is_available' => true,
        ]);
    }
}
