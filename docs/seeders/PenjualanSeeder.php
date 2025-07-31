<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have pelanggan data
        $pelangganIds = Pelanggan::pluck('id_pelanggan')->toArray();
        
        if (empty($pelangganIds)) {
            $this->command->info('No pelanggan found. Creating sample pelanggan first...');
            // Create sample pelanggan if none exist
            for ($i = 1; $i <= 5; $i++) {
                Pelanggan::create([
                    'nama' => 'Pelanggan ' . $i,
                    'username' => 'pelanggan' . $i,
                    'telepon' => '081234567' . sprintf('%02d', $i),
                    'alamat' => 'Alamat Pelanggan ' . $i,
                    'password' => 'password123',
                ]);
            }
            $pelangganIds = Pelanggan::pluck('id_pelanggan')->toArray();
        }

        // Create sample penjualan data for the last 30 days
        $penjualanData = [];
        
        for ($i = 1; $i <= 50; $i++) {
            // Random date within last 30 days
            $randomDate = Carbon::now()->subDays(rand(0, 30));
            
            // Random total_harga between 50,000 - 500,000
            $totalHarga = rand(50000, 500000);
            
            // Random discount 0-20%
            $diskon = $totalHarga * (rand(0, 20) / 100);
            
            $penjualanData[] = [
                'tanggal' => $randomDate->format('Y-m-d'),
                'id_pelanggan' => $pelangganIds[array_rand($pelangganIds)],
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        // Insert bulk data
        Penjualan::insert($penjualanData);

        $this->command->info('Created ' . count($penjualanData) . ' penjualan records.');
    }
}