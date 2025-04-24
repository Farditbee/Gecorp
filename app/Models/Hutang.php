<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hutang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hutang';
    protected $guarded = [''];
    public $primaryKey = 'id';

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function jenis_hutang()
    {
        return $this->belongsTo(JenisHutang::class,'id_jenis', 'id');
    }

    public function detailhutang()
    {
        return $this->hasMany(DetailHutang::class, 'id_hutang');
    }
}
