<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Company;
use App\Models\User; 
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Seed the menus table.
     *
     * @return void
     */
    public function run()
    {
        // Fetch users to associate with the menus
        $user = User::first(); 

        // Fetch categories, products, and companies to associate with the menus
        $category = Category::first();
        $product = Product::first();   
        $company = Company::first();   

        // Insert menus
        \DB::table('menus')->insert([
            [
                'category_id' => $category ? $category->id : null,
                'product_id'  => $product ? $product->id : null,
                'company_id'  => $company ? $company->id : null,
                'is_enable'   => 1,
                'created_by'  => $user ? $user->id : null,
                'updated_by'  => $user ? $user->id : null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => $category ? $category->id : null,
                'product_id'  => $product ? $product->id : null,
                'company_id'  => $company ? $company->id : null,
                'is_enable'   => 1,
                'created_by'  => $user ? $user->id : null,
                'updated_by'  => $user ? $user->id : null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
