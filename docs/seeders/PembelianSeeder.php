<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pembelian;
use Carbon\Carbon;

class PembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembelianData = [
            [
                'tanggal' => Carbon::now()->subDays(30),
                'id_supplier' => 1, // PT Kimia Farma
                'total_harga' => 840000.00,
                'diskon' => 15000.00,
            ],
            [
                'tanggal' => Carbon::now()->subDays(25),
                'id_supplier' => 2, // PT Kalbe Farma
                'total_harga' => 1560000.00,
                'diskon' => 35000.00,
            ],
            [
                'tanggal' => Carbon::now()->subDays(20),
                'id_supplier' => 3, // PT Dexa Medica
                'total_harga' => 2825000.00,
                'diskon' => 75000.00,
            ],
            [
                'tanggal' => Carbon::now()->subDays(15),
                'id_supplier' => 4, // PT Sanbe Farma
                'total_harga' => 525000.00,
                'diskon' => 10000.00,
            ],
            [
                'tanggal' => Carbon::now()->subDays(10),
                'id_supplier' => 5, // PT Indofarma
                'total_harga' => 525000.00,
                'diskon' => 25000.00,
            ],
            [
                'tanggal' => Carbon::now()->subDays(5),
                'id_supplier' => 1, // PT Kimia Farma
                'total_harga' => 360000.00,
                'diskon' => 0.00,
            ],
            [
                'tanggal' => Carbon::now()->subDays(3),
                'id_supplier' => 2, // PT Kalbe Farma
                'total_harga' => 800000.00,
                'diskon' => 20000.00,
            ],
            [
                'tanggal' => Carbon::now()->subDay(),
                'id_supplier' => 3, // PT Dexa Medica
                'total_harga' => 1350000.00,
                'diskon' => 50000.00,
            ]
        ];

        foreach ($pembelianData as $pembelian) {
            Pembelian::create($pembelian);
        }
    }
}
