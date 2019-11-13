<?php

namespace App;

use App\EventRegistration;
use Illuminate\Database\Eloquent\Model;

class EventPosition extends Model
{
    protected $table = 'event_positions';
    protected $fillable = ['id', 'event_id', 'name', 'created_at', 'updated_at'];

    public function getControllerAttribute() {
        $controllers = EventRegistration::where('position_id', $this->id)->where('status', 1)->orderBy('start_time', 'ASC')->get();

        return $controllers;
    }
}
