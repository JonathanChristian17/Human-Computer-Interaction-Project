<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Receptionist Accounts
        User::create([
            'name' => 'Admin Resepsionis',
            'email' => 'admin@penginapancahaya.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Danau Toba No. 123, Samosir',
        ]);

        User::create([
            'name' => 'Sarah Resepsionis',
            'email' => 'sarah@penginapancahaya.com',
            'password' => Hash::make('sarah123'),
            'role' => 'receptionist',
            'phone' => '081234567891',
            'address' => 'Jl. Danau Toba No. 123, Samosir',
        ]);

        // Create Customer Accounts
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('budi123'),
            'role' => 'customer',
            'phone' => '081298765432',
            'address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
        ]);

        User::create([
            'name' => 'Dewi Anggraini',
            'email' => 'dewi@gmail.com',
            'password' => Hash::make('dewi123'),
            'role' => 'customer',
            'phone' => '081387654321',
            'address' => 'Jl. Sudirman No. 78, Medan',
        ]);

        User::create([
            'name' => 'Rudi Hermawan',
            'email' => 'rudi@gmail.com',
            'password' => Hash::make('rudi123'),
            'role' => 'customer',
            'phone' => '081576543210',
            'address' => 'Jl. Asia No. 12, Medan',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('siti123'),
            'role' => 'customer',
            'phone' => '081665432109',
            'address' => 'Jl. Diponegoro No. 34, Siantar',
        ]);
    }
} 