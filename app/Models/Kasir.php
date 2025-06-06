<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kasir extends Model
{
    use HasFactory;

    protected $table = 'kasir';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function levelharga(): BelongsTo
    {
        return $this->belongsTo(LevelHarga::class, 'id_level_harga');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(StockBarang::class, 'id_barang');
    }

    public function detail_toko(): BelongsTo
    {
        return $this->belongsTo(DetailToko::class, 'id_barang');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function kasbon()
    {
        return $this->hasOne(Kasbon::class, 'id_kasir');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detailKasir(): BelongsTo
    {
        return $this->belongsTo(DetailKasir::class, 'id');
    }
}
