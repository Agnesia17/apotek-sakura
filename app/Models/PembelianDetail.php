<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_detail';
    public $incrementing = false;
    public $timestamps = true;

    // Disable primary key for composite key table
    protected $primaryKey = null;

    protected $fillable = [
        'id_pembelian',
        'id_obat',
        'jumlah'
    ];

    protected $casts = [
        'jumlah' => 'integer'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }

    // Accessor untuk menghitung subtotal berdasarkan harga obat
    public function getSubtotalAttribute()
    {
        if ($this->obat) {
            return $this->jumlah * $this->obat->harga_beli;
        }
        return 0;
    }

    // Accessor untuk mendapatkan harga satuan dari obat
    public function getHargaSatuanAttribute()
    {
        if ($this->obat) {
            return $this->obat->harga_beli;
        }
        return 0;
    }
}
