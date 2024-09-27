<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'promo';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';
}
