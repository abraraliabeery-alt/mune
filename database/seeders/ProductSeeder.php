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
            'name_en' => 'Espresso',
            'name_ar' => 'إسبريسو',
            'category' => 'Hot',
            'price' => 10.00,
            'description' => 'Strong and rich espresso shot.',
            'description_en' => 'Strong and rich espresso shot.',
            'description_ar' => 'جرعة إسبريسو قوية وغنية.',
            'image_url' => null,
            'is_available' => true,
        ]);

        Product::create([
            'name' => 'Cappuccino',
            'name_en' => 'Cappuccino',
            'name_ar' => 'كابتشينو',
            'category' => 'Hot',
            'price' => 14.00,
            'description' => 'Espresso with steamed milk and foam.',
            'description_en' => 'Espresso with steamed milk and foam.',
            'description_ar' => 'إسبريسو مع حليب مبخر ورغوة.',
            'image_url' => null,
            'is_available' => true,
        ]);

        Product::create([
            'name' => 'Iced Latte',
            'name_en' => 'Iced Latte',
            'name_ar' => 'لاتيه مثلج',
            'category' => 'Iced',
            'price' => 16.00,
            'description' => 'Chilled espresso with milk and ice.',
            'description_en' => 'Chilled espresso with milk and ice.',
            'description_ar' => 'إسبريسو بارد مع حليب وثلج.',
            'image_url' => null,
            'is_available' => true,
        ]);

        Product::create([
            'name' => 'Chocolate Brownie',
            'name_en' => 'Chocolate Brownie',
            'name_ar' => 'براوني شوكولاتة',
            'category' => 'Desserts',
            'price' => 12.00,
            'description' => 'Soft brownie with chocolate chunks.',
            'description_en' => 'Soft brownie with chocolate chunks.',
            'description_ar' => 'براوني طري مع قطع شوكولاتة.',
            'image_url' => null,
            'is_available' => true,
        ]);
    }
}
