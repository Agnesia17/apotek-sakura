<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'email' => 'admin@apotik.com',
                'password' => Hash::make('admin'),
                'role' => 'superadmin',
                'phone' => '081234567890',
                'address' => 'Jakarta',
                'email_verified_at' => now(),
            ]
        );

        // Create Apoteker
        User::updateOrCreate(
            ['username' => 'apoteker'],
            [
                'name' => 'Apoteker',
                'username' => 'apoteker',
                'email' => 'apoteker@apotik.com',
                'password' => Hash::make('apoteker'),
                'role' => 'apoteker',
                'phone' => '081234567891',
                'address' => 'Jakarta',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Super Admin - Username: admin, Password: admin');
        $this->command->info('Apoteker - Username: apoteker, Password: apoteker');
    }
}
