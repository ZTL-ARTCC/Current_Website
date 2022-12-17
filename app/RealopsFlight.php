<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RealopsFlight extends Model {
    protected $table = 'realops_flights';

    public $fillable = ['flight_number', 'dep_time', 'dep_airport', 'arr_airport', 'route', 'altitude'];

    public function getAssignedPilotAttribute() {
        return RealopsPilot::find($this->assigned_pilot_id);
    }

    public function getFlightDateFormattedAttribute() {
        return Carbon::parse($this->flight_date)->format('m/d/Y');
    }

    public function getDepTimeFormattedAttribute() {
        $dep_time_split = explode(':', $this->dep_time);
        return $dep_time_split[0] . ':' . $dep_time_split[1];
    }

    public function assignPilotToFlight($pilot_id) {
        $this->assigned_pilot_id = $pilot_id;
        $this->save();
    }

    public function removeAssignedPilot() {
        $this->assigned_pilot_id = null;
        $this->save();
    }
}
