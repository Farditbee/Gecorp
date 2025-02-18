<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kasbon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kasbon';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id');
    }

    public function kasir()
    {
        return $this->belongsTo(Kasir::class, 'id_kasir', 'id');
    }
}
