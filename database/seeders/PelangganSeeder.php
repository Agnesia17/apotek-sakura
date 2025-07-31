<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pelanggan')->insert([
            [
                'nama' => 'Ahmad Wijaya',
                'username' => 'ahmad_wijaya',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'telepon' => '08123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'username' => 'siti_nurhaliza',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'telepon' => '08234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'username' => 'budi_santoso',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Gatot Subroto No. 789, Surabaya',
                'telepon' => '08345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Maya Puspita',
                'username' => 'maya_puspita',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Ahmad Yani No. 321, Yogyakarta',
                'telepon' => '08456789012',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rizki Pratama',
                'username' => 'rizki_pratama',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Diponegoro No. 654, Medan',
                'telepon' => '08567890123',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}