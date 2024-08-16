<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Seed the categories table.
     *
     * @return void
     */
    public function run()
    {
        // Fetch a user to associate with the categories
        $user = User::first(); // Assuming at least one user exists

        // Insert categories
        Category::create([
            'name'             => 'Fast Food',
            'desc'             => 'Your meal with these tasty burgers',
            'type'             => 1,
            'slug'             => 'fast_food',
            'status'           => 1,
            'icon_file'        => 'assets/dummydata/fast-food.jpg',
            'background_image' => 'assets/dummydata/fast-food.jpg',
            'parent_id'        => null,
            'company_id'       => 1, 
            'created_by'       => $user->id,
            'updated_by'       => $user->id,
        ]);   

        Category::create([
            'name'             => 'Burger',
            'desc'             => 'Treats to your meal',
            'type'             => 2,
            'slug'             => 'best_burger',
            'status'           => 1,
            'icon_file'        => 'assets/dummydata/pizza.jpeg',
            'background_image' => 'assets/dummydata/pizza.jpeg',
            'parent_id'        => 1, 
            'company_id'       => 1,
            'created_by'       => $user->id,
            'updated_by'       => $user->id,
        ]);

    }
}
