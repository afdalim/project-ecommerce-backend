<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $bagsCategory = Category::where('slug', 'bags-accessories')->first();

        $products = [
            [
                'name' => 'CORALDAISY Premium Leather Bag',
                'slug' => 'coraldaisy-premium-leather-bag',
                'description' => 'Elegant and durable premium leather bag perfect for daily use. Made from high-quality leather with premium craftsmanship.',
                'short_description' => 'Premium leather bag - Perfect for daily use',
                'price' => 89.99,
                'cost' => 40.00,
                'sku' => 'CORALDAISY-001',
                'category_id' => $bagsCategory->id ?? 3,
                'brand' => 'CORALDAISY',
                'is_active' => true,
                'is_featured' => true,
                'rating' => 4.5,
                'total_reviews' => 24,
            ],
            [
                'name' => 'CORALDAISY Travel Backpack',
                'slug' => 'coraldaisy-travel-backpack',
                'description' => 'Spacious travel backpack with multiple compartments. Perfect for adventurers and travelers.',
                'short_description' => 'Travel backpack - Multiple compartments',
                'price' => 79.99,
                'cost' => 35.00,
                'sku' => 'CORALDAISY-002',
                'category_id' => $bagsCategory->id ?? 3,
                'brand' => 'CORALDAISY',
                'is_active' => true,
                'is_featured' => true,
                'rating' => 4.3,
                'total_reviews' => 18,
            ],
            [
                'name' => 'Classic Shoulder Bag',
                'slug' => 'classic-shoulder-bag',
                'description' => 'Timeless shoulder bag with elegant design. Perfect for office and casual outings.',
                'short_description' => 'Classic shoulder bag',
                'price' => 59.99,
                'cost' => 25.00,
                'sku' => 'BAG-SHOULDER-001',
                'category_id' => $bagsCategory->id ?? 3,
                'brand' => 'Fashion Co',
                'is_active' => true,
                'rating' => 4.0,
                'total_reviews' => 12,
            ],
            [
                'name' => 'Wallet Premium Edition',
                'slug' => 'wallet-premium-edition',
                'description' => 'Premium leather wallet with multiple card slots and coin pocket.',
                'short_description' => 'Premium leather wallet',
                'price' => 49.99,
                'cost' => 18.00,
                'sku' => 'WALLET-PREM-001',
                'category_id' => $bagsCategory->id ?? 3,
                'brand' => 'Accessories Plus',
                'is_active' => true,
                'rating' => 4.7,
                'total_reviews' => 35,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            Stock::create([
                'product_id' => $product->id,
                'quantity' => 100,
                'available' => 100,
                'reorder_level' => 10,
            ]);
        }
    }
}
