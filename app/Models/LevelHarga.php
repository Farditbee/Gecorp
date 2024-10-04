<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LevelHarga extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'level_harga';

    protected $guarded = [''];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function member()
    {
        return $this->hasMany(Member::class, 'id_level_harga');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'id_level_harga');
    }

    public function toko()
    {
        return $this->hasMany(Toko::class, 'id_level_harga', 'id');
    }

    public function stock()
    {
        return $this->hasMany(StockBarang::class, 'id_level_harga', 'id');
    }
}
