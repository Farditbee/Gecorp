<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mutasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mutasi';

    protected $guarded = [''];

    protected $keyType ='string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tokoPengirim(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko_pengirim', 'id');
    }

    public function tokoPenerima(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko_penerima', 'id');
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }
}
