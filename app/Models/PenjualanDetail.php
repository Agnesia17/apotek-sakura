<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_penjualan',
        'id_obat',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }

    // Mutator untuk menghitung subtotal otomatis
    public function setJumlahAttribute($value)
    {
        $this->attributes['jumlah'] = $value;
        if (isset($this->attributes['harga_satuan'])) {
            $this->attributes['subtotal'] = $value * $this->attributes['harga_satuan'];
        }
    }

    public function setHargaSatuanAttribute($value)
    {
        $this->attributes['harga_satuan'] = $value;
        if (isset($this->attributes['jumlah'])) {
            $this->attributes['subtotal'] = $this->attributes['jumlah'] * $value;
        }
    }
}