<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'       => 'Software Manager',
            'email'      => 'softwaremanager@gmail.com',
            'role'       => 1,
            'company_id' => NULL,
            'password'   => Hash::make('12345678'),
            'created_by' => 1,
        ]);
    }
}
