<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailKasbon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_kasbon';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'id_kasbon',
        'tgl_bayar',
        'bayar',
        'tipe_bayar',
    ];
    
    public function kasbon()
    {
        return $this->belongsTo(Kasbon::class, 'id_kasbon');
    }
}
