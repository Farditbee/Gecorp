<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemasukan';

    protected $guarded = [''];

    protected $keyType ='string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function jenis_pemasukan(): BelongsTo
    {
        return $this->belongsTo(JenisPemasukan::class,'id_jenis_pemasukan', 'id');
    }

    public function toko(): BelongsTo{
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function detail_pemasukan()
    {
        return $this->hasMany(DetailPemasukan::class, 'id_pemasukan', 'id')->withTrashed();
    }

    public function detailPemasukan()
    {
        return $this->hasMany(DetailPemasukan::class, 'id_pemasukan');
    }
}
