<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'nama_obat',
        'kategori',
        'brand',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'deskripsi',
        'image_url',
        'tanggal_kadaluarsa',
        'id_supplier'
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'tanggal_kadaluarsa' => 'date'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function spesifikasi()
    {
        return $this->hasOne(Spesifikasi::class, 'id_obat', 'id_obat');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_obat', 'id_obat');
    }

    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'id_obat', 'id_obat');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'id_obat', 'id_obat');
    }

    // Accessor untuk status kadaluarsa
    public function getStatusKadaluarsaAttribute()
    {
        if (!$this->tanggal_kadaluarsa) {
            return 'tidak_ada_data';
        }

        $today = Carbon::now()->startOfDay();
        $expiry = Carbon::parse($this->tanggal_kadaluarsa)->startOfDay();
        $diffInDays = $today->diffInDays($expiry, false);

        if ($diffInDays < 0) {
            return 'kadaluarsa';
        } elseif ($diffInDays <= 30) {
            return 'akan_kadaluarsa';
        } else {
            return 'aman';
        }
    }

    // Scope untuk obat yang masih aman
    public function scopeAman($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('tanggal_kadaluarsa')
                ->orWhere('tanggal_kadaluarsa', '>', Carbon::now()->addDays(30));
        });
    }

    // Scope untuk obat yang akan kadaluarsa
    public function scopeAkanKadaluarsa($query)
    {
        return $query->whereBetween('tanggal_kadaluarsa', [
            Carbon::now(),
            Carbon::now()->addDays(30)
        ]);
    }

    // Scope untuk obat yang sudah kadaluarsa
    public function scopeKadaluarsa($query)
    {
        return $query->where('tanggal_kadaluarsa', '<', Carbon::now());
    }

    // Method untuk mendapatkan class CSS berdasarkan status kadaluarsa
    public function getExpiryStatusClass()
    {
        $status = $this->status_kadaluarsa;

        switch ($status) {
            case 'kadaluarsa':
                return 'text-danger';
            case 'akan_kadaluarsa':
                return 'text-warning';
            case 'aman':
                return 'text-success';
            default:
                return 'text-muted';
        }
    }

    // Method untuk mendapatkan status kadaluarsa dalam bahasa Indonesia
    public function getExpiryStatus()
    {
        $status = $this->status_kadaluarsa;

        switch ($status) {
            case 'kadaluarsa':
                return 'Kadaluarsa';
            case 'akan_kadaluarsa':
                return 'Akan Kadaluarsa';
            case 'aman':
                return 'Aman';
            default:
                return 'Tidak Ada Data';
        }
    }

    // Method untuk mengecek apakah obat sudah kadaluarsa
    public function isExpired()
    {
        return $this->status_kadaluarsa === 'kadaluarsa';
    }

    // Method untuk mengecek apakah obat akan kadaluarsa
    public function isAboutToExpire()
    {
        return $this->status_kadaluarsa === 'akan_kadaluarsa';
    }
}
