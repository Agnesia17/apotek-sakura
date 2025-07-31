<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';

    protected $fillable = [
        'tanggal',
        'total_harga',
        'id_supplier',
        'diskon'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total_harga' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian', 'id_pembelian');
    }

    // Accessor untuk nomor purchase order
    public function getNoPurchaseOrderAttribute()
    {
        return 'PO-' . str_pad($this->id_pembelian, 6, '0', STR_PAD_LEFT);
    }

    // Scope untuk periode tertentu
    public function scopeByPeriod($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    // Scope untuk supplier tertentu
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('id_supplier', $supplierId);
    }
}
