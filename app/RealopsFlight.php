<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RealopsFlight extends Model {
    protected $table = 'realops_flights';

    public function getAssignedPilotAttribute() {
        return RealopsPilot::find($this->assigned_pilot_id);
    }

    public function getFlightDateFormattedAttribute() {
        return Carbon::parse($this->flight_date)->format('m/d/Y');
    }

    public function getDepTimeFormattedAttribute() {
        return $this->formatTime($this->dep_time);
    }

    public function getEstTimeEnrouteFormattedAttribute() {
        return $this->formatTime($this->est_time_enroute);
    }

    public function assignPilotToFlight($pilot_id) {
        $this->assigned_pilot_id = $pilot_id;
        $this->save();
    }

    public function removeAssignedPilot() {
        $this->assigned_pilot_id = null;
        $this->save();
    }

    private function formatTime($time) {
        if ($time == null) {
            return null;
        }

        $time_split = explode(':', $time);
        return $time_split[0] . ':' . $time_split[1];
    }

    public function getImageDirectory() {
        $flight_id = (!is_null($this->callsign)) ? $this->callsign : $this->flight_number;
        $directory = '/photos/airline_logos/' . substr($flight_id, 0, 3) . '.png';
        if (file_exists(public_path() . $directory)) {
            return $directory;
        }
        return '/photos/airline_logos/default.png';
    }
}
