<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Product; // Make sure you have a Product model
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Seed the products table.
     *
     * @return void
     */
    public function run()
    {
        // Fetch a user to associate with the product
        $user = User::first(); // Assuming at least one user exists

        // Fetch a category to associate with the product
        $category = Category::first();

        Product::create([
            'title'         => 'Cheeseburger Deluxe',
            'description'   => 'A delicious cheeseburger with all the toppings',
            'price'         => 9.99,
            'category_id'   => $category->id,
            'company_id'    => 1, 
            'is_enable'     => 1,
            'created_by'    => $user->id,
            'updated_by'    => $user->id,
        ]);

        Product::create([
            'title'         => 'Chicken Burger',
            'description'   => 'A juicy chicken burger with mayo',
            'price'         => 7.99,
            'category_id'   => $category->id,
            'company_id'    => 1, 
            'is_enable'     => 1,
            'created_by'    => $user->id,
            'updated_by'    => $user->id,
        ]);
    }
}
