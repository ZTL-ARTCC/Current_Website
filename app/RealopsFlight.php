<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Vite;

class RealopsFlight extends Model {
    protected $table = 'realops_flights';

    public function getFlightIdAttribute(): string {
        return (!is_null($this->callsign)) ? $this->callsign : $this->flight_number;
    }

    public function getAirlineAttribute(): string {
        return substr($this->flight_id, 0, 3);
    }

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

    private function timePart($time, $part): null|int {
        $time_parts = explode(':', $time);
        if (count($time_parts) == 0) {
            return null;
        } elseif ($part == 'h') {
            return (int) $time_parts[0];
        } elseif ($part == 'm') {
            return (int) $time_parts[1];
        }
        return null;
    }

    private function eta(): string {
        $etd = explode(':', $this->dep_time);
        $ete = explode(':', $this->est_time_enroute);
        if (count($etd) == 0 || count($ete) == 0) {
            return '0000';
        }
        $hours = $etd[0] + $ete[0];
        $minutes = $etd[1] + $ete[1];
        if ($minutes > 59) {
            $minutes -= 60;
            $hours++;
        }
        if ($hours > 23) {
            $hours -= 24;
        }
        return str_pad($hours, 2, '0', STR_PAD_LEFT) . str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    public function getImageDirectory() {
        $flight_id = (!is_null($this->callsign)) ? $this->callsign : $this->flight_number;
        $airline = strtoupper(substr($flight_id, 0, 3));
        if (file_exists(resource_path('/assets/img/airline_logos/' . $airline . '.png'))) {

            return Vite::image('airline_logos/' . $airline . '.png');
        }
        return Vite::image('airline_logos/default.png');
    }

    // ICAO flight plan reference: https://www.faa.gov/documentLibrary/media/Form/7233-4_02-07-24.pdf
    public function getIcaoFlightplanAttribute(): string {
        $icao_string = 'FPL-' . $this->flight_number . '-IS ';
        $icao_string .= '-' . $this->aircraft_type;
        $ac = (object) Aircraft::$data->where('ac_type', $this->aircraft_type)->first();
        if ($ac) {
            $icao_string .= '/' . $ac->icao_wtc . '-' . $ac->equipment . '/' . $ac->transponder;
        }
        $icao_string .= ' -' . $this->dep_airport . str_pad($this->timePart($this->dep_time, 'h'), 2, '0', STR_PAD_LEFT) . str_pad($this->timePart($this->dep_time, 'm'), 2, '0', STR_PAD_LEFT);
        $icao_string .= ' -' . PreferredRoute::routeLookup($this->dep_airport, $this->arr_airport);
        $icao_string .= ' -' . $this->arr_airport . $this->eta();
        $icao_string .= ' -DOF/' . Carbon::parse($this->flight_date)->format('ymd') . ' OPR/' . $this->airline;
        if ($ac) {
            $icao_string .= ' PBN/' . $ac->pbn . ' PER/' . $ac->perf_cat;
        }
        return '(' . $icao_string . ')';
    }

    // SimBrief reference: https://developers.navigraph.com/docs/simbrief/using-the-api#api-parameters
    public function getSimbriefParamsAttribute(): string {
        $pilot = RealopsPilot::find($this->assigned_pilot_id);
        $params = [
            'airline' => $this->airline,
            'fltnum' => substr($this->flight_number, 3),
            'type' => $this->aircraft_type,
            'orig' => $this->dep_airport,
            'dest' => $this->arr_airport,
            'date' => Carbon::parse($this->flight_date)->format('dMY'),
            'deph' => $this->timePart($this->dep_time, 'h'),
            'depm' => $this->timePart($this->dep_time, 'm'),
            'route' => PreferredRoute::routeLookup($this->dep_airport, $this->arr_airport),
            'steh' => $this->timePart($this->est_time_enroute, 'h'),
            'stem' => $this->timePart($this->est_time_enroute, 'm'),
            'callsign' => $this->callsign,
            'cpt' => $pilot->full_name,
            'pid' => $pilot->id,
            'acdata' => null
        ];
        $ac = (object) Aircraft::$data->where('ac_type', $this->aircraft_type)->first();
        if ($ac) {
            $ac_data = [
                "pbn" => $ac->pbn,
                "dof" => Carbon::parse($this->flight_date)->format('ymd'), // May need to remove this... SimBrief may generate the DOF from data above
                "opr" => $this->airline,
                "per" => $ac->perf_cat,
                "cat" => $ac->icao_wtc,
                "equip" => $ac->equipment,
                "transponder" => $ac->transponder
            ];
            $params['acdata'] = json_encode((object) $ac_data);
        }

        if (count($params) == 0) {
            return '';
        }
        return http_build_query($params);
    }
}
