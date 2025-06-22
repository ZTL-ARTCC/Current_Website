<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model {
    public $table = 'airports';
    public $fillable = ['id', 'name', 'ltr_4', 'ltr_3'];
    public $timestamps = false;

    public function getMetarAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if (isset($airport)) {
            return $airport->metar;
        } else {
            return 'N/A';
        }
    }

    public function getTafAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if (isset($airport)) {
            return $this->formatTaf($airport->taf);
        } else {
            return 'N/A';
        }
    }

    public function getVisualConditionsAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if (isset($airport)) {
            return $airport->visual_conditions;
        } else {
            return 'N/A';
        }
    }

    public function getWindAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if (isset($airport)) {
            return $airport->wind;
        } else {
            return 'N/A';
        }
    }

    public function getAltimeterAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if (isset($airport)) {
            return $airport->altimeter;
        } else {
            return 'N/A';
        }
    }

    public function getTemperatureDewpointAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if (isset($airport)) {
            return $airport->temp.'/'.$airport->dp;
        } else {
            return 'N/A';
        }
    }

    public function getInboundTrafficAttribute() {
        $client = new Client();
        $res = $client->get('https://ids.ztlartcc.org/FetchAirportInfo.php?id='.$this->ltr_4.'&type=arrival');
        $pilots = json_decode($res->getBody()->getContents(), true);

        if ($pilots) {
            return collect($pilots);
        } else {
            return null;
        }
    }

    public function getOutboundTrafficAttribute() {
        $client = new Client();
        $res = $client->get('https://ids.ztlartcc.org/FetchAirportInfo.php?id='.$this->ltr_4.'&type=departure');
        $pilots = json_decode($res->getBody()->getContents(), true);

        if ($pilots) {
            return collect($pilots);
        } else {
            return null;
        }
    }

    public static function formatTaf($taf) {
        $change_des = ['FM', 'TEMPO', 'PROB', 'BECMG', 'RMK'];
        $space = '<br>&nbsp;&nbsp;&nbsp;&nbsp;';

        foreach ($change_des as $change) {
            $taf = str_replace($change, $space . $change, $taf);
        }

        return $taf;
    }
}
