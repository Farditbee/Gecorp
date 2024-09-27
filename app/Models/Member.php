<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'member';

    protected $guarded = [''];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function leveluser(): BelongsTo
    {
        return $this->belongsTo(LevelUser::class, 'id_level');
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function levelharga(): BelongsTo
    {
        return $this->belongsTo(LevelHarga::class, 'id_level_harga');
    }
}
