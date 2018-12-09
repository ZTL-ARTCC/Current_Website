<?php

namespace App;

use App\deserialization;
use App\Metar;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    public $table = 'airports';
    public $fillable = ['id', 'name', 'ltr_4', 'ltr_3'];
    public $timestamps = false;

    public function getMetarAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if(isset($airport)) {
            return $airport->metar;
        } else {
            return 'N/A';
        }
    }

    public function getTafAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if(isset($airport)) {
            return $airport->taf;
        } else {
            return 'N/A';
        }
    }

    public function getVisualConditionsAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if(isset($airport)) {
            return $airport->visual_conditions;
        } else {
            return 'N/A';
        }
    }

    public function getWindAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if(isset($airport)) {
            return $airport->wind;
        } else {
            return 'N/A';
        }
    }

    public function getAltimeterAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if(isset($airport)) {
            return $airport->altimeter;
        } else {
            return 'N/A';
        }
    }

    public function getTemperatureDewpointAttribute() {
        $airport = Metar::where('icao', $this->ltr_4)->first();

        if(isset($airport)) {
            return $airport->temp.'/'.$airport->dp;
        } else {
            return 'N/A';
        }
    }

    public function getInboundTrafficAttribute() {
        $client = new Client();
        $res = $client->get('http://api.vateud.net/online/arrivals/'.$this->ltr_4.'.json');
        $pilots = json_decode($res->getBody()->getContents(), true);

        if($pilots) {
            return collect($pilots);
        } else {
            return null;
        }
    }

    public function getOutboundTrafficAttribute() {
        $client = new Client();
        $res = $client->get('http://api.vateud.net/online/departures/'.$this->ltr_4.'.json');
        $pilots = json_decode($res->getBody()->getContents(), true);

        if($pilots) {
            return collect($pilots);
        } else {
            return null;
        }
    }

    public function getChartsAttribute() {
        $apt = $this->ltr_4;
        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', 'https://api.aircharts.org/v2/Airport/'.$apt);
        $status = $res->getStatusCode();

        if($status == 404) {
            return null;
        } elseif(isset(json_decode($res->getBody())->$apt->charts) == true) {
            return json_decode($res->getBody())->$apt->charts;
        } else {
            return null;
        }
    }
}
