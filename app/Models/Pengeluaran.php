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
}
