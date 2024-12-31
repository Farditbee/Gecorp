<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataReture extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_retur';

    protected $fillable = [
        'id_users',
        'id_toko',
        'no_nota',
        'tgl_retur',
    ];

    protected $keyType = 'string';

    public $primaryKey = 'id';
}
