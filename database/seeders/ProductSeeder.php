<?php

namespace Database\Seeders;

use App\Models\Category;
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
        // Membuat category contoh
        Category::create(['name' => 'Snacks']);
        Category::create(['name' => 'Mie Instant']);
        Category::create(['name' => 'Minuman']);

        // Membuat Product contoh
        Product::create([
            'category_id' => 1,
            'name' => 'Chitato',
            'description' => 'Keripik kentang rasa original',
            'price' => 15000,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => 2,
            'name' => 'Indomie Goreng',
            'description' => 'Mie instant rasa goreng',
            'price' => 3500,
            'stock' => 12,
        ]);

        Product::create([
            'category_id' => 3,
            'name' => 'Frisian Flag Susu UHT',
            'description' => 'Rasa Vanilla',
            'price' => 7800,
            'stock' => 20,
        ]);
    }
}
