<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_pengeluaran';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_jenis_pengeluaran', 'id');
    }
}
