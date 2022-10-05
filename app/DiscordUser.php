<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordUser extends Model {
    protected $table = 'discord_users';

    protected $hidden = ['id', 'created_at', 'updated_at'];
}
