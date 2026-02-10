<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        $newIn = Category::create([
            'name' => 'NEW IN',
            'slug' => 'new-in',
            'type' => 'main',
            'description' => 'Latest arrivals and new products',
            'sort_order' => 1,
        ]);

        $denim = Category::create([
            'name' => 'DENIM',
            'slug' => 'denim',
            'type' => 'main',
            'description' => 'Premium Japanese selvedge denim collection',
            'sort_order' => 2,
        ]);

        $americana = Category::create([
            'name' => 'AMERICANA',
            'slug' => 'americana',
            'type' => 'main',
            'description' => 'Classic American workwear and heritage pieces',
            'sort_order' => 3,
        ]);

        $accessories = Category::create([
            'name' => 'ACCESSORIES',
            'slug' => 'accessories',
            'type' => 'main',
            'sort_order' => 4,
        ]);

        $sale = Category::create([
            'name' => 'SALE',
            'slug' => 'sale',
            'type' => 'main',
            'description' => 'Discounted items and special offers',
            'sort_order' => 5,
        ]);

        // Subcategories for DENIM
        Category::create([
            'name' => 'Selvedge Jeans',
            'slug' => 'selvedge-jeans',
            'type' => 'sub',
            'parent_id' => $denim->id,
            'description' => 'Premium selvedge denim jeans',
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Denim Jackets',
            'slug' => 'denim-jackets',
            'type' => 'sub',
            'parent_id' => $denim->id,
            'description' => 'Classic denim jackets and trucker styles',
            'sort_order' => 2,
        ]);

        // Subcategories for AMERICANA
        Category::create([
            'name' => 'Shirts & Flannels',
            'slug' => 'shirts-flannels',
            'type' => 'sub',
            'parent_id' => $americana->id,
            'description' => 'Button-up shirts and flannel shirts',
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Outerwear',
            'slug' => 'outerwear',
            'type' => 'sub',
            'parent_id' => $americana->id,
            'description' => 'Jackets, coats, and outerwear',
            'sort_order' => 2,
        ]);

        Category::create([
            'name' => 'Tees & Henleys',
            'slug' => 'tees-henleys',
            'type' => 'sub',
            'parent_id' => $americana->id,
            'description' => 'T-shirts, henleys, and casual tops',
            'sort_order' => 3,
        ]);
    }
}
