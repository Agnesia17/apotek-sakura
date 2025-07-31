<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Obat;
use Carbon\Carbon;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obatData = [
            [
                'nama_obat' => 'Paracetamol 500mg',
                'kategori' => 'Analgesik',
                'brand' => 'Sanbe',
                'satuan' => 'Strip',
                'harga_beli' => 2500.00,
                'harga_jual' => 4000.00,
                'stok' => 150,
                'deskripsi' => 'Obat pereda nyeri dan penurun demam',
                'image_url' => 'images/paracetamol.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18), // Aman
                'id_supplier' => 4
            ],
            [
                'nama_obat' => 'Amoxicillin 500mg',
                'kategori' => 'Antibiotik',
                'brand' => 'Kimia Farma',
                'satuan' => 'Kapsul',
                'harga_beli' => 8000.00,
                'harga_jual' => 12000.00,
                'stok' => 80,
                'deskripsi' => 'Antibiotik untuk infeksi bakteri',
                'image_url' => 'images/amoxicillin.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->subDays(15), // Kadaluarsa
                'id_supplier' => 1
            ],
            [
                'nama_obat' => 'Vitamin C 1000mg',
                'kategori' => 'Vitamin',
                'brand' => 'Kalbe',
                'satuan' => 'Tablet',
                'harga_beli' => 15000.00,
                'harga_jual' => 22000.00,
                'stok' => 200,
                'deskripsi' => 'Suplemen vitamin C untuk daya tahan tubuh',
                'image_url' => 'images/vitamin-c.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addDays(20), // Akan kadaluarsa
                'id_supplier' => 2
            ],
            [
                'nama_obat' => 'Omeprazole 20mg',
                'kategori' => 'Antasida',
                'brand' => 'Dexa Medica',
                'satuan' => 'Kapsul',
                'harga_beli' => 5500.00,
                'harga_jual' => 8500.00,
                'stok' => 100,
                'deskripsi' => 'Obat untuk mengatasi masalah lambung',
                'image_url' => 'images/omeprazole.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(8), // Aman
                'id_supplier' => 3
            ],
            [
                'nama_obat' => 'Cetirizine 10mg',
                'kategori' => 'Antihistamin',
                'brand' => 'Indofarma',
                'satuan' => 'Tablet',
                'harga_beli' => 3000.00,
                'harga_jual' => 5000.00,
                'stok' => 120,
                'deskripsi' => 'Obat alergi dan anti gatal',
                'image_url' => 'images/cetirizine.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->subMonths(2), // Kadaluarsa
                'id_supplier' => 5
            ],
            [
                'nama_obat' => 'Ibuprofen 400mg',
                'kategori' => 'NSAID',
                'brand' => 'Kalbe',
                'satuan' => 'Tablet',
                'harga_beli' => 4000.00,
                'harga_jual' => 6500.00,
                'stok' => 90,
                'deskripsi' => 'Anti inflamasi dan pereda nyeri',
                'image_url' => 'images/ibuprofen.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addDays(25), // Akan kadaluarsa
                'id_supplier' => 2
            ],
            [
                'nama_obat' => 'Salbutamol Inhaler',
                'kategori' => 'Bronkodilator',
                'brand' => 'Dexa Medica',
                'satuan' => 'Inhaler',
                'harga_beli' => 45000.00,
                'harga_jual' => 65000.00,
                'stok' => 25,
                'deskripsi' => 'Inhaler untuk asma dan sesak napas',
                'image_url' => 'images/salbutamol.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24), // Aman
                'id_supplier' => 3
            ],
            [
                'nama_obat' => 'CTM 4mg',
                'kategori' => 'Antihistamin',
                'brand' => 'Sanbe',
                'satuan' => 'Tablet',
                'harga_beli' => 1500.00,
                'harga_jual' => 2500.00,
                'stok' => 180,
                'deskripsi' => 'Obat alergi dan flu',
                'image_url' => 'images/ctm.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addDays(10), // Akan kadaluarsa
                'id_supplier' => 4
            ],
            [
                'nama_obat' => 'Metformin 500mg',
                'kategori' => 'Antidiabetes',
                'brand' => 'Kimia Farma',
                'satuan' => 'Tablet',
                'harga_beli' => 6000.00,
                'harga_jual' => 9000.00,
                'stok' => 60,
                'deskripsi' => 'Obat diabetes tipe 2',
                'image_url' => 'images/metformin.jpg',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(12), // Aman
                'id_supplier' => 1
            ],
            [
                'nama_obat' => 'Captopril 25mg',
                'kategori' => 'ACE Inhibitor',
                'brand' => 'Indofarma',
                'satuan' => 'Tablet',
                'harga_beli' => 3500.00,
                'harga_jual' => 5500.00,
                'stok' => 75,
                'deskripsi' => 'Obat hipertensi',
                'image_url' => 'images/captopril.jpg',
                'tanggal_kadaluarsa' => null, // Tidak ada tanggal kadaluarsa
                'id_supplier' => 5
            ]
        ];

        // Update existing data instead of truncate
        foreach ($obatData as $index => $obat) {
            $id_obat = $index + 1; // Assuming IDs start from 1
            
            // Update existing obat or create new one
            $existingObat = Obat::find($id_obat);
            if ($existingObat) {
                $existingObat->update($obat);
            } else {
                Obat::create($obat);
            }
        }
    }
}
