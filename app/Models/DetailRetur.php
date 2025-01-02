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
}
