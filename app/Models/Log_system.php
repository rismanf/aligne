<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log_system extends Model
{
    protected $fillable = ['user_id', 'ip', 'event', 'extra', 'additional'];
    
    public static function record($user_id = null, $event, $extra, $additional = null)
    {
        //1, Add News, Add News success {{name}}
        return static::create([
            'user_id' => is_null($user_id) ? null : $user_id->id,
            'ip' => request()->ip(),
            'event' => $event,
            'extra' => $extra,
            'additional' => $additional
        ]);
    }
}
