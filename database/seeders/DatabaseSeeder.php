<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === Users untuk Login ===
        User::firstOrCreate(
            ['email' => 'admin@kedai.com'],
            [
                'name' => 'Admin Kedai',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@kedai.com'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'role' => 'kasir',
                'is_active' => true,
            ]
        );
    }
}
