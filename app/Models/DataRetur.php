<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataRetur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'DataRetur';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';
}
