<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test customers
        Pelanggan::updateOrCreate(
            ['username' => 'customer1'],
            [
                'nama' => 'John Doe',
                'username' => 'customer1',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'password' => Hash::make('customer1'),
            ]
        );

        Pelanggan::updateOrCreate(
            ['username' => 'customer2'],
            [
                'nama' => 'Jane Smith',
                'username' => 'customer2',
                'telepon' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta',
                'password' => Hash::make('customer2'),
            ]
        );

        Pelanggan::updateOrCreate(
            ['username' => 'pelanggan'],
            [
                'nama' => 'Pelanggan Demo',
                'username' => 'pelanggan',
                'telepon' => '081234567892',
                'alamat' => 'Jl. Demo No. 789, Jakarta',
                'password' => Hash::make('pelanggan'),
            ]
        );

        $this->command->info('Customer users created successfully!');
        $this->command->info('Customer 1 - Username: customer1, Password: customer1');
        $this->command->info('Customer 2 - Username: customer2, Password: customer2');
        $this->command->info('Demo Customer - Username: pelanggan, Password: pelanggan');
    }
}
