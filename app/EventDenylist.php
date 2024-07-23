<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDenylist extends Model {
    protected $table = 'event_denylists';
    protected $fillable = ['id', 'event_name', 'vatsim_id', 'created_at', 'updated_at'];
}
