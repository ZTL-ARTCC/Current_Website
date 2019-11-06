<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overflight extends Model
{
    protected $table = 'flights_within_artcc';
    protected $fillable = ['id', 'pilot_cid', 'pilot_name', 'callsign', 'type', 'dep', 'arr', 'route', 'created_at', 'updated_at'];
}
