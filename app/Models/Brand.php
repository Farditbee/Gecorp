<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'brand';

    protected $fillable = ['nama_brand', 'id_jenis_barang'];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public $timestamps = false;

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_barang', 'id');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class, 'id_jenis_barang');
    }

}
