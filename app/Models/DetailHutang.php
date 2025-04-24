<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailHutang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_hutang';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function hutang(): BelongsTo
    {
        return $this->belongsTo(Hutang::class, 'id_hutang', 'id');
    }
}
