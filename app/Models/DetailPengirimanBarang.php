<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPengirimanBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_pengiriman_barang';

    public $timestamps = false;

    protected $fillable = [
        'id_pengiriman_barang',
        'id_barang',
        'nama_barang',
        'qty',
        'harga',
        'total_harga'
    ];

    public function pengiriman()
    {
        return $this->belongsTo(PengirimanBarang::class, 'id_pengiriman_barang');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
