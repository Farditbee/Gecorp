<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisHutang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_hutang';
    protected $guarded = [''];

    public function hutang()
    {
        return $this->hasMany(Hutang::class, 'id_hutang', 'id');
    }
}
