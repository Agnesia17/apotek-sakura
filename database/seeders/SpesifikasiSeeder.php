<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Spesifikasi;

class SpesifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spesifikasiData = [
            [
                'id_obat' => 1, // Paracetamol
                'kandungan' => 'Paracetamol 500mg',
                'bentuk_sediaan' => 'Tablet',
                'kemasan' => '10 tablet per strip',
                'satuan' => 'Strip',
                'cara_kerja' => 'Menghambat sintesis prostaglandin di sistem saraf pusat',
                'penyimpanan' => 'Simpan di tempat kering, suhu ruang (15-30°C)'
            ],
            [
                'id_obat' => 2, // Amoxicillin
                'kandungan' => 'Amoxicillin trihydrate 500mg',
                'bentuk_sediaan' => 'Kapsul',
                'kemasan' => '10 kapsul per strip',
                'satuan' => 'Kapsul',
                'cara_kerja' => 'Antibiotik beta-laktam yang menghambat sintesis dinding sel bakteri',
                'penyimpanan' => 'Simpan di tempat sejuk dan kering, terhindar dari cahaya'
            ],
            [
                'id_obat' => 3, // Vitamin C
                'kandungan' => 'Ascorbic acid 1000mg',
                'bentuk_sediaan' => 'Tablet effervescent',
                'kemasan' => '10 tablet per tube',
                'satuan' => 'Tablet',
                'cara_kerja' => 'Antioksidan yang membantu pembentukan kolagen dan meningkatkan daya tahan tubuh',
                'penyimpanan' => 'Simpan di tempat kering, tutup rapat setelah dibuka'
            ],
            [
                'id_obat' => 4, // Omeprazole
                'kandungan' => 'Omeprazole 20mg',
                'bentuk_sediaan' => 'Kapsul enterik',
                'kemasan' => '10 kapsul per strip',
                'satuan' => 'Kapsul',
                'cara_kerja' => 'Proton pump inhibitor yang mengurangi produksi asam lambung',
                'penyimpanan' => 'Simpan di tempat kering, suhu ruang, terhindar dari cahaya'
            ],
            [
                'id_obat' => 5, // Cetirizine
                'kandungan' => 'Cetirizine HCl 10mg',
                'bentuk_sediaan' => 'Tablet salut selaput',
                'kemasan' => '10 tablet per strip',
                'satuan' => 'Tablet',
                'cara_kerja' => 'Antihistamin H1 yang menghambat reaksi alergi',
                'penyimpanan' => 'Simpan di tempat kering, suhu ruang (15-30°C)'
            ],
            [
                'id_obat' => 6, // Ibuprofen
                'kandungan' => 'Ibuprofen 400mg',
                'bentuk_sediaan' => 'Tablet salut selaput',
                'kemasan' => '10 tablet per strip',
                'satuan' => 'Tablet',
                'cara_kerja' => 'NSAID yang menghambat COX-1 dan COX-2 untuk mengurangi inflamasi',
                'penyimpanan' => 'Simpan di tempat kering, suhu ruang, terhindar dari cahaya langsung'
            ],
            [
                'id_obat' => 7, // Salbutamol
                'kandungan' => 'Salbutamol sulfate 100mcg per dose',
                'bentuk_sediaan' => 'Inhalasi aerosol',
                'kemasan' => '200 doses per inhaler',
                'satuan' => 'Inhaler',
                'cara_kerja' => 'Beta-2 agonist yang merelaksasi otot polos bronkus',
                'penyimpanan' => 'Simpan pada suhu ruang, jangan dibekukan atau terkena panas berlebih'
            ],
            [
                'id_obat' => 8, // CTM
                'kandungan' => 'Chlorpheniramine maleate 4mg',
                'bentuk_sediaan' => 'Tablet',
                'kemasan' => '10 tablet per strip',
                'satuan' => 'Tablet',
                'cara_kerja' => 'Antihistamin generasi pertama yang menghambat reseptor H1',
                'penyimpanan' => 'Simpan di tempat kering, suhu ruang (15-30°C)'
            ],
            [
                'id_obat' => 9, // Metformin
                'kandungan' => 'Metformin HCl 500mg',
                'bentuk_sediaan' => 'Tablet salut selaput',
                'kemasan' => '10 tablet per strip',
                'satuan' => 'Tablet',
                'cara_kerja' => 'Menurunkan produksi glukosa hepatik dan meningkatkan sensitivitas insulin',
                'penyimpanan' => 'Simpan di tempat kering, suhu ruang, terhindar dari kelembaban'
            ],
            [
                'id_obat' => 10, // Captopril
                'kandungan' => 'Captopril 25mg',
                'bentuk_sediaan' => 'Tablet',
                'kemasan' => '10 tablet per strip',
                'satuan' => 'Tablet',
                'cara_kerja' => 'ACE inhibitor yang menghambat konversi angiotensin I menjadi angiotensin II',
                'penyimpanan' => 'Simpan di tempat sejuk dan kering, terhindar dari cahaya'
            ]
        ];

        foreach ($spesifikasiData as $spesifikasi) {
            Spesifikasi::create($spesifikasi);
        }
    }
}