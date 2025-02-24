<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class,'id_brand_barang', 'id');
    }
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class, 'id_jenis_barang', 'id');
    }
    public function stockBarang(): HasMany
    {
        return $this->hasMany(StockBarang::class, 'id_barang', 'id');
    }

    public function detail_toko(): HasMany
    {
        return $this->hasMany(DetailToko::class, 'id_barang', 'id');
    }
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_barang', 'id');
    }

    public function detail_pengiriman_barang(): HasMany
    {
        return $this->hasMany(DetailPengirimanBarang::class, 'id_barang', 'id');
    }

    public function level_harga()
    {
        return $this->hasMany(LevelHarga::class, 'id_barang', 'id');
    }

    public function promo()
    {
        return $this->hasMany(Promo::class, 'id_barang');
    }

    public function dt_retur()
    {
        return $this->hasMany(DetailRetur::class, 'id_barang');
    }

}
