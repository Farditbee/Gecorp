<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'toko';

    protected $guarded = [''];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';


    public function detail_toko()
    {
        return $this->hasMany(DetailToko::class, 'id_toko', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_toko', 'id');
    }

    public function pengirimanSebagaiPengirim()
    {
        return $this->hasMany(PengirimanBarang::class, 'toko_pengirim', 'id')->with('tokos');
    }

    public function pengirimanSebagaiPenerima()
    {
        return $this->hasMany(PengirimanBarang::class, 'toko_penerima', 'id');
    }

    public function stok()
    {
        return $this->hasMany(StockBarang::class, 'toko_pengirim', 'id');
    }

    // public function levelHarga()
    // {
    //     return $this->hasMany(LevelHarga::class, 'id_toko');
    // }
    public function levelHarga()
    {
        return $this->belongsTo(LevelHarga::class, 'id_toko');
    }

    public function kasir()
    {
        return $this->hasMany(Kasir::class, 'id_toko', 'id');
    }
}
