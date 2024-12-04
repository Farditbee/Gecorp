<?php 

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $parameters = [])
    {
        if (Auth::check()) {
            $user = Auth::user();
            ActivityLog::create([
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'action' => $action,
                'parameters' => json_encode($parameters),
                'action_time' => now(),
            ]);
        }
    }
}