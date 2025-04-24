<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPiutang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_piutang';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class, 'id_piutang', 'id');
    }
}
