<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Guild extends Model {
    use Notifiable;
    protected $table = null;

    public static function routeNotificationForDiscord() {
        return config('discord.ots_channel_id');
    }
}
