<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDenylist extends Model {
    protected $table = 'event_denylists';
    protected $fillable = ['id', 'vatsim_id', 'created_at', 'updated_at'];
}
