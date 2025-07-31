<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama',
        'username',
        'telepon',
        'alamat',
        'password',
        'tanggal_lahir',
        'jenis_kelamin'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return $this->nama;
    }

    // Accessor untuk umur
    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        return $this->tanggal_lahir->age;
    }
}