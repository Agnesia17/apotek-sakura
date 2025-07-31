<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PembelianDetail;

class PembelianDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembelianDetailData = [
            // Pembelian 1 - PT Kimia Farma
            [
                'id_pembelian' => 1,
                'id_obat' => 2, // Amoxicillin
                'jumlah' => 50,
            ],
            [
                'id_pembelian' => 1,
                'id_obat' => 9, // Metformin
                'jumlah' => 30,
            ],
            
            // Pembelian 2 - PT Kalbe Farma
            [
                'id_pembelian' => 2,
                'id_obat' => 3, // Vitamin C
                'jumlah' => 40,
            ],
            [
                'id_pembelian' => 2,
                'id_obat' => 6, // Ibuprofen
                'jumlah' => 60,
            ],
            
            // Pembelian 3 - PT Dexa Medica
            [
                'id_pembelian' => 3,
                'id_obat' => 4, // Omeprazole
                'jumlah' => 100,
            ],
            [
                'id_pembelian' => 3,
                'id_obat' => 7, // Salbutamol
                'jumlah' => 50,
            ],
            
            // Pembelian 4 - PT Sanbe Farma
            [
                'id_pembelian' => 4,
                'id_obat' => 1, // Paracetamol
                'jumlah' => 100,
            ],
            [
                'id_pembelian' => 4,
                'id_obat' => 8, // CTM
                'jumlah' => 80,
            ],
            
            // Pembelian 5 - PT Indofarma
            [
                'id_pembelian' => 5,
                'id_obat' => 5, // Cetirizine
                'jumlah' => 75,
            ],
            [
                'id_pembelian' => 5,
                'id_obat' => 10, // Captopril
                'jumlah' => 60,
            ],
            
            // Pembelian 6 - PT Kimia Farma
            [
                'id_pembelian' => 6,
                'id_obat' => 2, // Amoxicillin
                'jumlah' => 30,
            ],
            [
                'id_pembelian' => 6,
                'id_obat' => 9, // Metformin
                'jumlah' => 30,
            ],
            
            // Pembelian 7 - PT Kalbe Farma
            [
                'id_pembelian' => 7,
                'id_obat' => 3, // Vitamin C
                'jumlah' => 20,
            ],
            [
                'id_pembelian' => 7,
                'id_obat' => 6, // Ibuprofen
                'jumlah' => 100,
            ],
            
            // Pembelian 8 - PT Dexa Medica
            [
                'id_pembelian' => 8,
                'id_obat' => 4, // Omeprazole
                'jumlah' => 50,
            ],
            [
                'id_pembelian' => 8,
                'id_obat' => 7, // Salbutamol
                'jumlah' => 20,
            ]
        ];

        foreach ($pembelianDetailData as $detail) {
            PembelianDetail::create($detail);
        }
    }
}