<?php

namespace App\Console\Commands;

use App\FeatureToggle;
use App\RealopsFlight;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class FlightawareAPI extends Command implements PromptsForMissingInput {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Realops:LoadFromFlightaware {incoming} {airport} {from} {to} {max}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads flights for realops from Flightaware AeroAPI';

    /**
     * Execute the console command.
     */
    public function handle(): void {
        if (!FeatureToggle::isEnabled("realops")) {
            $this->error("realops is not enabled");
            return;
        }
        $start_time = microtime(true);

        $this->info("Loading a maximum of " . $this->argument("max") . " " . ($this->argument("incoming") == "true" ? "arriving" : "departing") . " flights for " . $this->argument("airport") . " from " . $this->argument("from") . " to " . $this->argument("to"));

        $url = Config::get('flightaware.base') . '/aeroapi/schedules/' . $this->argument('from') . '/' . $this->argument('to') . "?" . ($this->argument("incoming") == "true" ? "destination" : "origin") . "=" . $this->argument("airport") . "&max_pages=2";
        $req_num = 1;
        $flights = [];

        $client = new Client([
            'headers' => ['x-apikey' => 'V0dZYHRwU0kYy2HYc4VjxKBigfuuynIe']
        ]);

        $ratelimit_amount = 150; // seems to ratelimit us if we hit 150 requests
        $ratelimit_timeout = 60; // its a per-one-minute span, so wait a minute before going again

        // start the request loop to fetch all of the flights scheduled during this time
        while (true) {
            if (count($flights) >= $this->argument("max")) {
                $this->info("Capping at " . $this->argument("max") . " flights");
                break;
            }
            if (count($flights) % $ratelimit_amount == 0 && count($flights) != 0) {
                $this->info("Ratelimit timeout: " . $ratelimit_timeout . " seconds");
                for ($i = 1; $i < $ratelimit_timeout; $i++) {
                    sleep(1);
                    if ($i % 10 == 0) {
                        $this->info("Ratelimit timeout: " . $ratelimit_timeout-$i . " seconds remain");
                    }
                }
            }

            $this->info("Request #" . $req_num . ": " . $url . " (" . count($flights) . " flights so far)");

            $res = $client->request('GET', $url);
            $data = json_decode($res->getBody(), true);

            $flights = array_merge($flights, $data['scheduled']);
            $url = Config::get("flightaware.base") . "/aeroapi" . $data['links']['next'];

            if (empty($data['scheduled'])) {
                break;
            }

            $req_num += 1;
        }

        $this->info("Loaded " . count($flights) . " flights in " . $req_num . " requests");

        foreach ($flights as $num => $flight) {
            $this->info("Saving " . $num+1 . "/" . count($flights));

            $callsign = $flight['ident'];

            $existing = RealopsFlight::where('flight_number', $callsign)->first();
            if ($existing !== null) {
                $this->info("Skipping already processed flight ".$callsign);
                continue;
            } // this event has already been processed

            // insert
            $new_flight = new RealopsFlight;

            // parse the departure and arrival times
            $deptime = $flight['scheduled_out'];
            $arrtime = $flight['scheduled_in'];
            $deptime_parsed = Carbon::parse($deptime);
            $arrtime_parsed = Carbon::parse($arrtime);

            $new_flight->assigned_pilot_id = null;
            $new_flight->flight_number = $callsign;
            $new_flight->flight_date = $deptime_parsed->toDateString();
            $new_flight->dep_time = $deptime_parsed->toTimeString();
            $new_flight->dep_airport = $flight['origin'];
            $new_flight->arr_airport = $flight['destination'];
            $new_flight->est_arr_time = $arrtime_parsed->toTimeString();
            $new_flight->route = 'Pilot Choice';

            $new_flight->save();
        }

        $end_time = microtime(true);

        $this->info(count($flights) . ' flights imported from FlightAware in ' . $end_time - $start_time . ' seconds');
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing() {
        return [
            'incoming' => 'Fetch arriving flights (true) or departing flights (false)?',
            'airport' => 'What airport should flights be fetched for (ICAO)?',
            'from' => 'Start date of flights to fetch',
            'to' => 'End date of flights to fetch',
            'max' => 'Maximum amount of flights to fetch'
        ];
    }
}
