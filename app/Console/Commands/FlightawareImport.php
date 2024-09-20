<?php

namespace App\Console\Commands;

use App\FeatureToggle;
use App\RealopsFlight;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Helper\ProgressBar;

class FlightawareImport extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Realops:LoadFromFlightaware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flightaware import task. Interactive and VERY SLOW.';

    public function calculateChunks($start_time, $chunk_count, $optimal_chunk_len_seconds) {
        $pbar_chunks = $this->output->createProgressBar($chunk_count * 2);
        $pbar_chunks->start();

        $chunks_unix = [];
        $chunks = [];

        // This is, admittedly, really janky.
        // Doing it with Carbon didn't work though.
        // This does, so ¯\_(ツ)_/

        $current_time = $start_time->timestamp;

        for ($i = 0; $i < $chunk_count; $i++) {
            $old_time = $current_time;
            $current_time += $optimal_chunk_len_seconds;
            $new_time = $current_time;

            $chunks_unix[] = [$old_time, $new_time];

            $pbar_chunks->advance();
        }

        for ($y = 0; $y < $chunk_count; $y++) {
            $chunks[] = [Carbon::createFromTimestamp($chunks_unix[$y][0]), Carbon::createFromTimestamp($chunks_unix[$y][1])];
            $pbar_chunks->advance();
        }

        $pbar_chunks->finish();
        $this->info(''); // blank line after progress bar

        return $chunks;
    }

    public function save_flights($pbar, $flights) {
        for ($i = 0; $i < sizeof($flights); $i++) {
            $flight = $flights[$i];
            $pbar->setMessage('Saving ' . $flight['ident']);

            $flight_number = $flight['ident'];

            $existing = RealopsFlight::where('flight_number', $flight_number)->first();
            if ($existing !== null) {
                continue;
            }
            $new_flight = new RealopsFlight;

            // parse the departure and arrival times
            $deptime = $flight['scheduled_out'];
            $arrtime = $flight['scheduled_in'];
            $deptime_parsed = Carbon::parse($deptime);
            $arrtime_parsed = Carbon::parse($arrtime);

            $ete = $deptime_parsed->diff($arrtime_parsed);

            $new_flight->assigned_pilot_id = null;
            $new_flight->flight_number = $flight_number;
            $new_flight->callsign = $this->parse_regionals($flight['actual_ident']);
            $new_flight->flight_date = $deptime_parsed->toDateString();
            $new_flight->dep_time = $deptime_parsed->toTimeString();
            $new_flight->dep_airport = $flight['origin'];
            $new_flight->arr_airport = $flight['destination'];
            $new_flight->est_time_enroute = $ete->format('%H:%M:%S');
            $new_flight->gate = 'Pilot Choice';

            $new_flight->save();
            $pbar->advance();
        }
    }

    public function load_departures(&$flights, $ratelimit_max_flights, $ratelimit_timeout, $client, $chunk, $pbar, &$flights_count) {
        // First, load as many pages of departures as is configured
        for ($i = 0; $i < intval(Config::get('flightaware.departure_pages_per_chunk')); $i++) {
            // If we hit the ratelimit, pause for a bit.
            // Commenting out the ratelimit timeout logic - this needs to be rebuilt to consider a chunk returning zero flights
            //if ($flights_count > $ratelimit_max_flights) {
                $pbar->setMessage('Ratelimit timeout');
                $pbar->advance();
                $flights_count = 0;
                sleep($ratelimit_timeout);
            //}

            // Generate the URL for the HTTP client.
            // Format: %BASE/schedules/FROM/TO/?origin=KATL
            $url = Config::get('flightaware.base') . '/schedules/' . $chunk[0]->format('Y-m-d\TH:i:s') . '/' . $chunk[1]->format('Y-m-d\TH:i:s') . '?origin=KATL?include_codeshares=FALSE&include_regional=FALSE';
            $pbar->setMessage($url);

            if (!Config::get('flightaware.dryrun')) {
                $res = $client->request('GET', $url);
                $data = json_decode($res->getBody(), true);

                $flights = array_merge($flights, $data['scheduled']); // add actual flights to $flights
                $flights_count += sizeof($data['scheduled']); // then increase the counter used for ratelimit compliance
            }

            $pbar->advance();
        }
    }

    public function load_arrivals(&$flights, $ratelimit_max_flights, $ratelimit_timeout, $client, $chunk, $pbar, &$flights_count) {
        // First, load as many pages of arrivals as is configured
        for ($i = 0; $i < intval(Config::get('flightaware.arrival_pages_per_chunk')); $i++) {
            // If we hit the ratelimit, pause for a bit.
            if ($flights_count > $ratelimit_max_flights) {
                $pbar->setMessage('Ratelimit timeout');
                $pbar->advance();
                $flights_count = 0;
                sleep($ratelimit_timeout);
            }

            // Generate the URL for the HTTP client.
            // Format: %BASE/schedules/FROM/TO/?destination=KATL
            $url = Config::get('flightaware.base') . '/schedules/' . $chunk[0]->format('Y-m-d\TH:i:s') . '/' . $chunk[1]->format('Y-m-d\TH:i:s') . '?destination=KATL?include_codeshares=FALSE&include_regional=FALSE';
            $pbar->setMessage($url);

            if (!Config::get('flightaware.dryrun')) {
                $res = $client->request('GET', $url);
                $data = json_decode($res->getBody(), true);

                $flights = array_merge($flights, $data['scheduled']); // add actual flights to $flights
                $flights_count += sizeof($data['scheduled']); // then increase the counter used for ratelimit compliance
            }

            $pbar->advance();
        }
    }

    /**
     * Execute the console command.
     */
    public function handle(): void {
        if (!FeatureToggle::isEnabled("realops")) {
            $this->error("Realops is not enabled");
            return;
        }

        /*
         * Import Task: Steps
         * (Planning) 1. Collect Information
         * (Planning) 2. Create Chunks
         * (Fetching) 3. Load Chunks
         */

        $start_time = Carbon::parse(Config::get('flightaware.start_date'));
        $end_time = Carbon::parse(Config::get('flightaware.end_date'));

        $duration = abs($end_time->diffInSeconds($start_time));

        $this->info('[CollectInformation] Event duration ' . $duration . ' seconds.');

        // we are going to pull some amount of pages for every chunk. means we want to distribute (n1 / n2) chunks across the time period
        // First, how many flights are we going to pull per chunk?
        $departure_flights_per_chunk = intval(Config::get('flightaware.flights_per_page')) * intval(Config::get('flightaware.departure_pages_per_chunk'));
        $arrival_flights_per_chunk = intval(Config::get('flightaware.flights_per_page')) * intval(Config::get('flightaware.arrival_pages_per_chunk'));
        $flights_per_chunk = $departure_flights_per_chunk + $arrival_flights_per_chunk;

        // Next, how many chunks can we have before we hit our configured flight limit?
        $target_flights = intval(Config::get('flightaware.max_flights'));
        // Conditional protects against division by zero error in chunk length calc when target_flights is a low number
        $chunk_count = (floor($target_flights / $flights_per_chunk) > 0) ? floor($target_flights / $flights_per_chunk) : 1;

        // Based on all of that, third, what is the optimal chunk length to maximize flight count?
        $optimal_chunk_len_seconds = floor($duration / $chunk_count);

        $this->info('[CreateChunks] Optimal chunk length: ' . $optimal_chunk_len_seconds . 's, number of chunks: ' . $chunk_count . ' to target ' . Config::get('flightaware.max_flights') . ' flights');
        $this->info('[CreateChunks] Event will be ' . $chunk_count * $optimal_chunk_len_seconds . 's long');

        $chunks = $this->calculateChunks($start_time, $chunk_count, $optimal_chunk_len_seconds);

        $this->info('[LoadChunks] Loading all data from FlightAware. Do not stop the script for ANY REASON after this point! Your api credits will not be returned.');

        $pages_per_chunk = intval(Config::get('flightaware.departure_pages_per_chunk')) + intval(Config::get('flightaware.arrival_pages_per_chunk'));
        $flights_total = $flights_per_chunk * $chunk_count;

        // Calculating steps for the progress bar
        $steps_per_chunk = $pages_per_chunk + $flights_per_chunk;

        $ratelimit_max_flights = intval(Config::get('flightaware.ratelimit_count'));
        $ratelimit_timeout = intval(Config::get('flightaware.ratelimit_timeout'));

        $ratelimit_timeout_count = floor($flights_total / $ratelimit_max_flights);

        $total_steps = ($steps_per_chunk * $chunk_count) + $ratelimit_timeout;

        $client = null;
        if (Config::get('flightaware.dryrun')) {
            $this->warn('[LoadChunks] Performing dry-run. No data will actually be loaded and FlightAware will not be contacted.');
            $this->warn('[LoadChunks] Set FLIGHTAWARE_DRYRUN to false to run for real.');
        } else {
            $client = new Client([
                'headers' => ['x-apikey' => Config::get('flightaware.api_key')]
            ]);
        }

        $this->info('[LoadChunks] Ratelimit information: ' . $ratelimit_max_flights . ' flights per ' . $ratelimit_timeout . ' seconds. ' . $flights_total . ' flights will need ' . $ratelimit_timeout_count . ' timeouts.');

        ProgressBar::setFormatDefinition('custom', '%bar% %current%/%max% -- %message%');

        $pbar = $this->output->createProgressBar($total_steps);
        $pbar->setFormat('custom');

        $flights_count = 0;

        for ($y = 0; $y < $chunk_count; $y++) {
            $chunk = $chunks[$y];

            $pbar->setMessage('Departures, chunk #' . $y);

            $flights = [];

            $this->load_departures($flights, $ratelimit_max_flights, $ratelimit_timeout, $client, $chunk, $pbar, $flights_count);

            // Rinse and repeat, for arrivals.

            $pbar->setMessage('Arrivals, chunk #' . $y);

            $this->load_arrivals($flights, $ratelimit_max_flights, $ratelimit_timeout, $client, $chunk, $pbar, $flights_count);

            $pbar->setMessage('Saving flights for chunk #' . $y);

            // Then, save every flight we got so far.
            $this->save_flights($pbar, $flights);
        }

        $this->info("Import task finished. No errors.");
    }

    /**
     * Parse Delta Connection flight numbers to regional carrier callsigns.
     */
    public function parse_regionals($callsign): string|null {
        if (!preg_match('/^DAL|DL\d{4}/', $callsign)) {
            return $callsign;
        }
        $flight_id = (int)filter_var($callsign, FILTER_SANITIZE_NUMBER_INT);
        if (($flight_id >= 3500) && ($flight_id <= 4338)) {
            return 'SKW' . $flight_id;
        }
        if (($flight_id >= 4439) && ($flight_id <= 4627)) {
            return 'SKW' . $flight_id;
        }
        if (($flight_id >= 4628) && ($flight_id <= 5589)) {
            return 'EDV' . $flight_id;
        }
        if (($flight_id >= 5590) && ($flight_id <= 5910)) {
            return 'RPA' . $flight_id;
        }
        return $callsign;
    }
}
