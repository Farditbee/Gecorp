<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'promo';

    protected $guarded = [''];

    public $timestamps = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }
}
