<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailStockBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_stock';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $guarded = [];
}
