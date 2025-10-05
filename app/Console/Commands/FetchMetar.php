<?php

namespace App\Console\Commands;

use App\Airport;
use App\Metar;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchMetar extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Weather:UpdateWeather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates weather with the ADDS weather service.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $airports_icao = Airport::get()->pluck('ltr_4');
        $airports_full = Airport::get();
        $airports = $airports_icao->toArray();

        $client = new Client;
        $response = $client->request('GET', 'https://aviationweather.gov/api/data/metar?format=json&taf=true&ids='.implode(',', $airports));
        $res_json = json_decode($response->getBody());
        
        DB::table('airport_weather')->truncate();
        foreach ($res_json as $res) {
            $airport = new Metar;
            $airport->icao = $res->icaoId;

            $wind = 'CALM';

            if ($res->wspd > 0) {
                $wind = ($res->wdir != 'VRB' && $res->wdir < 100 ? '0' : '') . $res->wdir;
                $wind .= '@' . ($res->wspd < 10 ? '0' : '') . $res->wspd;

                if (isset($res->wgst)) {
                    $wind .= 'G' . $res->wgst;
                }
            }

            $airport->visual_conditions = $res->fltCat;
            $airport->wind = $wind;
            $airport->altimeter = $this->hpa_to_inhg($res->altim);
            $airport->metar = $res->rawOb;
            $airport->temp = $res->temp;
            $airport->dp = $res->dewp;
            $airport->taf = $res->rawTaf;
            $airport->save();
        }
    }

    private function hpa_to_inhg($hpa) {
        return number_format($hpa * 0.02953, 2);
    }
}
