<?php

namespace App\Event;

use Illuminate\Database\Eloquent\Model;

class PositionPreset extends Model
{
    protected $table = 'position_presets';
    protected $fillable = ['id', 'name', 'first_position', 'last_position', 'created_at', 'updated_at'];
}
