<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPiutang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_piutang';
    protected $guarded = [''];

    public function piutang()
    {
        return $this->hasMany(Piutang::class, 'id_piutang', 'id');
    }
}
