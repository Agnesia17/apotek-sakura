<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'tanggal',
        'total_harga',
        'id_pelanggan',
        'diskon',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total_harga' => 'decimal:2'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }

    // Accessor untuk nomor invoice
    public function getNoInvoiceAttribute()
    {
        return 'INV-' . str_pad($this->id_penjualan, 6, '0', STR_PAD_LEFT);
    }

    // Scope untuk status tertentu
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk periode tertentu
    public function scopeByPeriod($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    // Method untuk mendapatkan opsi status penjualan
    public static function getStatusOptions()
    {
        return [
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'pending' => 'Pending'
        ];
    }

    // Method untuk mendapatkan label status dengan warna
    public function getStatusLabel()
    {
        $statusLabels = [
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'pending' => 'Pending'
        ];

        return $statusLabels[$this->status] ?? 'Unknown';
    }

    // Method untuk mendapatkan class badge berdasarkan status
    public function getStatusBadgeClass()
    {
        $statusBadgeClasses = [
            'diproses' => 'bg-warning',
            'selesai' => 'bg-success',
            'dibatalkan' => 'bg-danger',
            'pending' => 'bg-secondary'
        ];

        return $statusBadgeClasses[$this->status] ?? 'bg-secondary';
    }

    // Method untuk mendapatkan class CSS berdasarkan status
    public function getStatusClass()
    {
        $statusClasses = [
            'diproses' => 'text-warning',
            'selesai' => 'text-success',
            'dibatalkan' => 'text-danger',
            'pending' => 'text-secondary'
        ];

        return $statusClasses[$this->status] ?? 'text-secondary';
    }

    // Method untuk mengecek apakah transaksi sudah selesai
    public function isCompleted()
    {
        return $this->status === 'selesai';
    }

    // Method untuk mengecek apakah transaksi dibatalkan
    public function isCancelled()
    {
        return $this->status === 'dibatalkan';
    }

    // Method untuk mengecek apakah transaksi masih diproses
    public function isProcessing()
    {
        return $this->status === 'diproses';
    }

    // Method untuk mengecek apakah transaksi pending
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
