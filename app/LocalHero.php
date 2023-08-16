<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalHero extends Bronze {
    protected $table = 'local_hero';
    protected $fillable = ['id', 'controller_id', 'month', 'year', 'month_hours', 'updated_at', 'created_at'];
}
