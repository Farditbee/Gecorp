<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPemasukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_pemasukan';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'id_pemasukan', 'id');
    }
}
