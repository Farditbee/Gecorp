<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LevelUser extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'level_users';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    public $primaryKey = 'id';

    public function user()
    {
        return $this->hasMany(User::class, 'id_level');
    }

    public function levelusers()
    {
        return $this->hasMany(LevelUser::class, 'id_level');
    }

}
