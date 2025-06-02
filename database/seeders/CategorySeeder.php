<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Home & Garden',
            'Beauty & Personal Care',
            'Sports & Outdoors',
            'Books',
            'Toys & Games',
            'Food & Beverages'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => 'Products related to ' . $category,
                'is_active' => true,
            ]);
        }
    }
}
