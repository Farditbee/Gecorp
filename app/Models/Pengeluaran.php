<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengeluaran';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function jenis_pengeluaran(): BelongsTo
    {
        return $this->belongsTo(JenisPengeluaran::class,'id_jenis_pengeluaran', 'id');
    }

    public function toko(): BelongsTo{
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function detail_pengeluaran()
    {
        return $this->hasMany(DetailPengeluaran::class, 'id_pengeluaran', 'id');
    }

    public function detailPengeluaran()
    {
        return $this->hasMany(DetailPengeluaran::class, 'id_pengeluaran');
    }
    
}
