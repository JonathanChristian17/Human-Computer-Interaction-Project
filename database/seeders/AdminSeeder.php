<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@penginapan-cahaya.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@penginapan-cahaya.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'email_verified_at' => now(),
            ]);
        }
    }
} 