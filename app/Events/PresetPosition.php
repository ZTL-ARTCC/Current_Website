<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;

class PresetPosition extends Model
{
    protected $table = 'preset_positions';
    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
}
