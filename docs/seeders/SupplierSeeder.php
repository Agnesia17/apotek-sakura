<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'nama_supplier' => 'PT Kimia Farma',
                'alamat' => 'Jl. Veteran No. 9, Jakarta Pusat',
                'kota' => 'Jakarta',
                'telepon' => '021-3456789'
            ],
            [
                'nama_supplier' => 'PT Kalbe Farma',
                'alamat' => 'Jl. Letjen Suprapto Kav. 4, Jakarta Pusat',
                'kota' => 'Jakarta',
                'telepon' => '021-4270888'
            ],
            [
                'nama_supplier' => 'PT Dexa Medica',
                'alamat' => 'Jl. Bambu Kuning Raya No. 1, Palembang',
                'kota' => 'Palembang',
                'telepon' => '0711-710123'
            ],
            [
                'nama_supplier' => 'PT Sanbe Farma',
                'alamat' => 'Jl. Soekarno Hatta No. 482, Bandung',
                'kota' => 'Bandung',
                'telepon' => '022-5400200'
            ],
            [
                'nama_supplier' => 'PT Indofarma',
                'alamat' => 'Jl. Letjen MT Haryono Kav. 11, Jakarta Selatan',
                'kota' => 'Jakarta',
                'telepon' => '021-7992345'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
