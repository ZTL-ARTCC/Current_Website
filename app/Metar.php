<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metar extends Model
{
    protected $table = 'airport_weather';
    protected $fillable = ['id', 'icao', 'metar', 'taf', 'visual_conditions', 'altimeter', 'wind', 'temp', 'dp', 'created_at', 'updated_at'];
}
