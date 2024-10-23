<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_barang';

    protected $fillable = ['id_barang', 'nama_barang', 'stock', 'harga_satuan', 'hpp_awal', 'hpp_baru', 'nilai_total', 'level_harga'];

    protected $guarded = [];

    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function levelharga()
    {
        return $this->hasMany(LevelHarga::class, 'id_barang', 'id');
    }

}
