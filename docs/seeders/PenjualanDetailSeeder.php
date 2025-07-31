<?php

namespace Database\Seeders;

use App\Models\PenjualanDetail;
use App\Models\Penjualan;
use App\Models\Obat;
use Illuminate\Database\Seeder;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all penjualan IDs
        $penjualanIds = Penjualan::pluck('id_penjualan')->toArray();
        
        // Get all obat IDs
        $obatIds = Obat::pluck('id_obat')->toArray();
        
        if (empty($penjualanIds)) {
            $this->command->error('No penjualan found. Please run PenjualanSeeder first.');
            return;
        }
        
        if (empty($obatIds)) {
            $this->command->error('No obat found. Please run ObatSeeder first.');
            return;
        }

        $detailData = [];
        $createdDetails = 0;

        // For each penjualan, create random detail items
        foreach ($penjualanIds as $penjualanId) {
            // Each penjualan has 1-5 different obat items
            $itemCount = rand(1, 5);
            $usedObatIds = [];
            
            for ($i = 0; $i < $itemCount; $i++) {
                // Select random obat that hasn't been used in this penjualan
                $availableObats = array_diff($obatIds, $usedObatIds);
                
                if (empty($availableObats)) {
                    break; // No more unique obat available
                }
                
                $selectedObatId = $availableObats[array_rand($availableObats)];
                $usedObatIds[] = $selectedObatId;
                
                // Random quantity between 1-10
                $jumlah = rand(1, 10);
                
                $detailData[] = [
                    'id_penjualan' => $penjualanId,
                    'id_obat' => $selectedObatId,
                    'jumlah' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $createdDetails++;
                
                // Batch insert every 100 records for performance
                if (count($detailData) >= 100) {
                    PenjualanDetail::insert($detailData);
                    $detailData = [];
                }
            }
        }
        
        // Insert remaining data
        if (!empty($detailData)) {
            PenjualanDetail::insert($detailData);
        }

        $this->command->info("Created {$createdDetails} penjualan detail records.");
        
        // Update total_harga in penjualan based on actual detail calculations
        $this->updatePenjualanTotals();
    }
    
    /**
     * Update penjualan total_harga based on actual detail calculations
     */
    private function updatePenjualanTotals(): void
    {
        $penjualans = Penjualan::with(['penjualanDetail.obat'])->get();
        
        foreach ($penjualans as $penjualan) {
            $totalHarga = 0;
            
            foreach ($penjualan->penjualanDetail as $detail) {
                if ($detail->obat) {
                    $totalHarga += $detail->obat->harga_jual * $detail->jumlah;
                }
            }
            
            // Keep existing discount, just update base total
            $penjualan->update([
                'total_harga' => $totalHarga
            ]);
        }
        
        $this->command->info('Updated penjualan totals based on actual detail calculations.');
    }
}