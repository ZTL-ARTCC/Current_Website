<?php

namespace App\Console\Commands;

use App\Airport;
use App\Metar;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use SimpleXMLElement;

class FetchMetar extends Command
{
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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $airports_icao = Airport::get()->pluck('ltr_4');
        $airports_full = Airport::get();
        $airports = $airports_icao->toArray();

        $client = new Client;
        $response_metars = $client->request('GET', 'https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=metars&requestType=retrieve&format=xml&hoursBeforeNow=2&mostRecentForEachStation=true&stationString='.implode(',', $airports));
        $response_tafs = $client->request('GET', 'https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=tafs&requestType=retrieve&format=xml&hoursBeforeNow=2&mostRecentForEachStation=true&stationString='.implode(',', $airports));

        $root_metars = new SimpleXMLElement($response_metars->getBody());
        $root_tafs = new SimpleXMLElement($response_tafs->getBody());

        $i = 0;
		
		DB::table('airport_weather')->truncate();
        foreach($root_metars->data->children() as $metar) {
			$airport = new Metar;
			$airport->icao = $metar->station_id->__toString();

            $wind = 'CALM';

            if ($metar->wind_dir_degrees->__toString() > 0 && $metar->wind_dir_degrees->__toString() < 100) {
                $winds = "0" . $metar->wind_dir_degrees->__toString();
            } else {
                $winds = $metar->wind_dir_degrees->__toString();
            }

            if ($winds > 0 && $metar->wind_speed_kt->__toString() > 0) {
                if($metar->wind_speed_kt->__toString() < 10) {
                    $windspeed = '0'.$metar->wind_speed_kt->__toString();
                } else {
                    $windspeed = $metar->wind_speed_kt->__toString();
                }
                $wind = $winds . '@' . $windspeed;

                if ($metar->wind_gust_kt) {
                    $wind .= "G" . $metar->wind_gust_kt->__toString();
                }
            }

            $airport->visual_conditions = $metar->flight_category->__toString();
            $airport->wind = $wind;
            $airport->altimeter = number_format((double)$metar->altim_in_hg, 2);
            $airport->metar = $metar->raw_text->__toString();
            $airport->temp = $metar->temp_c->__toString();
            $airport->dp = $metar->dewpoint_c->__toString();
            $airport->save();

            $i++;
        }

        foreach($root_tafs->data->children() as $taf) {
            $airport = Metar::where('icao', $taf->station_id)->first();
            $airport->taf = $taf->raw_text->__toString();
            $airport->save();

            $i++;
        }
    }
}
