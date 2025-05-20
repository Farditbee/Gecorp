<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailKasir extends Model
{
    use HasFactory;

    protected $table = 'detail_kasir';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(Kasir::class, 'id_kasir');
    }

    public function detailPembelian(): BelongsTo
    {
        return $this->belongsTo(DetailPembelianBarang::class, 'id_detail_pembelian');
    }
}
