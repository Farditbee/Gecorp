<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengirimanBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengiriman_barang';

    protected $fillable = [
        'no_resi',
        'toko_pengirim',
        'nama_pengirim',
        'ekspedisi',
        'toko_penerima',
        'tgl_kirim',
        'tgl_terima',
        'total_item',
        'total_nilai',
        'status',
        'tipe_pengiriman',
    ];

    public function tokof()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_pengirim', 'id');
    }

    public function tokos()
    {
        return $this->belongsTo(Toko::class, 'toko_penerima', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nama_pengirim', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPengirimanBarang::class, 'id_pengiriman_barang');
    }
}
