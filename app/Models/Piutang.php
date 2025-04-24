<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piutang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'piutang';
    protected $guarded = [''];
    public $primaryKey = 'id';

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function jenis_piutang()
    {
        return $this->belongsTo(JenisPiutang::class,'id_jenis', 'id');
    }

    public function detailpiutang()
    {
        return $this->hasMany(DetailPiutang::class, 'id_piutang');
    }
}
