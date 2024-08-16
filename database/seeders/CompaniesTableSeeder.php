<?php

namespace Database\Seeders;

use App\Models\User; // Import the User model if needed for created_by and updated_by fields
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Seed the companies table.
     *
     * @return void
     */
    public function run()
    {
        // Fetch a user to associate with the companies
        $user = User::first(); 

        // Insert companies
        \DB::table('companies')->insert([
            [
                'name'             => 'AA Resturants',
                'email'            => 'aaresturant@gmail.com',
                'address'          => 'Islamabad',
                'is_enable'        => 1,
                'token'            => Str::random(60), 
                'subscription_date'=> now(),
                'status'           => 1, 
                'created_by'       => $user ? $user->id : null,
                'updated_by'       => $user ? $user->id : null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'BB Resturant',
                'email'            => 'bbresturant@gmail.com',
                'address'          => 'Lahore',
                'is_enable'        => 1,
                'token'            => Str::random(60), 
                'subscription_date'=> now(),
                'status'           => 1, 
                'created_by'       => $user ? $user->id : null,
                'updated_by'       => $user ? $user->id : null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],

        ]);
    }
}
