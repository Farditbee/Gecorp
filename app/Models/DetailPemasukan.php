<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPemasukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_pemasukan';

    protected $guarded = [''];

    protected $keyType = 'string';

    public $primaryKey = 'id';

    public function pemasukan(): BelongsTo
    {
        return $this->belongsTo(Pemasukan::class, 'id_pemasukan', 'id');
    }
}
