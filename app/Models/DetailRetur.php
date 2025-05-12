<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailRetur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_retur';

    protected $fillable = [
        'id_users',
        'id_retur',
        'id_transaksi',
        'id_barang',
        'no_nota',
        'qty',
        'harga',
    ];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function retur(){
        return $this->belongsTo(DataReture::class, 'id_retur');
    }

    public function pembelian()
    {
        return $this->hasOne(DetailPembelianBarang::class, 'qrcode', 'qrcode');
    }

}
