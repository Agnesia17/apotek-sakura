<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spesifikasi extends Model
{
    use HasFactory;

    protected $table = 'spesifikasi';
    protected $primaryKey = 'id_obat';
    public $incrementing = false;

    protected $fillable = [
        'id_obat',
        'kandungan',
        'bentuk_sediaan',
        'kemasan',
        'satuan',
        'cara_kerja',
        'penyimpanan',
        'komposisi',
        'indikasi',
        'kontraindikasi',
        'efek_samping',
        'dosis',
        'dosis_dewasa',
        'dosis_anak',
        'cara_pakai',
        'peringatan'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}