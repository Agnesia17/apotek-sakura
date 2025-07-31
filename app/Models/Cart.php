<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_pelanggan',
        'id_obat',
        'jumlah',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }


    // Scope untuk cart pelanggan tertentu
    public function scopeByPelanggan($query, $pelangganId)
    {
        return $query->where('id_pelanggan', $pelangganId);
    }

    // Static method untuk menghitung total cart
    public static function getTotalByPelanggan($pelangganId)
    {
        return static::join('obat', 'cart.id_obat', '=', 'obat.id_obat')
            ->where('cart.id_pelanggan', $pelangganId)
            ->selectRaw('SUM(cart.jumlah * obat.harga_jual) as total')
            ->value('total') ?? 0;
    }

    // Static method untuk menghitung jumlah item dalam cart
    public static function getItemCountByPelanggan($pelangganId)
    {
        return static::where('id_pelanggan', $pelangganId)->sum('jumlah');
    }
}