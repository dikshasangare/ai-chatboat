<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15',
                'category' => 'Phone',
                'price' => 79999,
                'stock' => 10,
                'description' => 'Apple smartphone with premium performance.',
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'category' => 'Phone',
                'price' => 74999,
                'stock' => 7,
                'description' => 'Premium Android smartphone with AI features.',
            ],
            [
                'name' => 'Redmi Note 13',
                'category' => 'Phone',
                'price' => 18999,
                'stock' => 25,
                'description' => 'Budget smartphone with good battery life.',
            ],
            [
                'name' => 'MacBook Air M3',
                'category' => 'Laptop',
                'price' => 114999,
                'stock' => 5,
                'description' => 'Lightweight laptop for productivity.',
            ],
            [
                'name' => 'Dell Inspiron 15',
                'category' => 'Laptop',
                'price' => 58999,
                'stock' => 8,
                'description' => 'Office and student friendly laptop.',
            ],
            [
                'name' => 'ASUS TUF Gaming Laptop',
                'category' => 'Laptop',
                'price' => 79999,
                'stock' => 4,
                'description' => 'Gaming laptop with dedicated graphics.',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category' => 'Headphones',
                'price' => 29999,
                'stock' => 12,
                'description' => 'Premium noise cancelling headphones.',
            ],
            [
                'name' => 'Boat Rockerz 450',
                'category' => 'Headphones',
                'price' => 1499,
                'stock' => 30,
                'description' => 'Affordable wireless headphones.',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
