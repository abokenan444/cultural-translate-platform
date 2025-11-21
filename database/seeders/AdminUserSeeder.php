<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@culturaltranslate.com',
            'password' => Hash::make('Admin2024!'),
            'role' => 'super_admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
