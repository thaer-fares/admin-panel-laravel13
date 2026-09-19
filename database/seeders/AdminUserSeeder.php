<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('Admin');
    }
}
