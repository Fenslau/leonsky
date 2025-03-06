<?php

namespace App\Models;

use App\Events\ServerStatusUpdated;
use Illuminate\Database\Eloquent\Model;

class ServerStatus extends Model
{
    protected $guarded = [];

    protected $casts = [
        'login' => 'boolean',
        'game' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::updating(function ($status) {
            if ($status->isDirty('login')) {
                $newLoginStatus = $status->login ? 'login on' : 'login off';
                event(new ServerStatusUpdated($newLoginStatus));
            }
            if ($status->isDirty('game')) {
                $newGameStatus = $status->login ? 'game on' : 'game off';
                event(new ServerStatusUpdated($newGameStatus));
            }
        });
    }
}
