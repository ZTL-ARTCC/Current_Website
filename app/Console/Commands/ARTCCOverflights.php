<?php

namespace App\Console\Commands;

use App\Class\PointInPolygon;
use App\Overflight;
use Config;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ARTCCOverflights extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Overflights:GetOverflights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the overflights for the specified ARTCC.';

    protected $statusUrl = "https://data.vatsim.net/v3/vatsim-data.json";

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
        $datafeed = $this->getDatafeed();
        if (is_null($datafeed) || !property_exists($datafeed, 'pilots')) {
            return 1;
        }

        DB::table('flights_within_artcc')->truncate();

        $aircraft = [];
        foreach ($datafeed->pilots as $pilot) {
            $aircraft[] = [$pilot->latitude, $pilot->longitude];
        }

        $pip = new PointInPolygon($aircraft);
        foreach ($datafeed->pilots as $ind => $pilot) {
            if ($pip->points_in_polygon[$ind] == Config::get('vatusa.facility')) {
                $flight = new Overflight;
                $flight->pilot_cid = $pilot->cid;
                $flight->pilot_name = $pilot->name;
                $flight->callsign = $pilot->callsign;
                if (property_exists($pilot, 'flight_plan') && !is_null($pilot->flight_plan)) {
                    $flight->type = $pilot->flight_plan->aircraft_faa;
                    $flight->dep = $pilot->flight_plan->departure;
                    $flight->arr = $pilot->flight_plan->arrival;
                    $flight->route = $pilot->flight_plan->route;
                } else {
                    $flight->type = '---';
                    $flight->dep = '---';
                    $flight->arr = '---';
                    $flight->route = '---';
                }
                $flight->save();
            }
        }
    }

    public function getDatafeed() {
        $client = new Client();
        $res = $client->request('GET', $this->statusUrl);
        $data = json_decode($res->getBody());
        return $data;
    }
}
