<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_pengeluaran';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id');
    }
}