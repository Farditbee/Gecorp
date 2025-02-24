<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailToko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_toko';

    protected $fillable = ['id_barang','qrcode', 'id_supplier', 'nama_barang', 'id_toko', 'qty', 'harga'];

    public $incrementing = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

}
