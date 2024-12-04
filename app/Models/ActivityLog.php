<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ip_address', 'action', 'parameters', 'action_time',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];
}
