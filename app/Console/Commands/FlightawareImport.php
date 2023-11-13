<?php

namespace App\Console\Commands;

use App\FeatureToggle;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

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

    /**
     * Execute the console command.
     */
    public function handle(): void {
        if (!FeatureToggle::isEnabled("realops")) {
            $this->error("Realops is not enabled");
            return;
        }

        $this->info('[CollectInformation] Step 1/3: Collecting Information');

        /*
         * Import Task: Steps
         * (Planning) 1. Collect Information
         * (Planning) 2. Create Chunks
         * (Fetching) 3. Load Chunks
         */

        $start_time = Carbon::parse(Config::get('flightaware.start_date'));
        $end_time = Carbon::parse(Config::get('flightaware.end_date'));

        $this->info('[CollectInformation] Import task: importing flights ' . $start_time . ' to ' . $end_time);

        $duration = $end_time->diffInSeconds($start_time);

        $this->info('[CollectInformation] Event duration ' . $duration . ' seconds.');

        $this->info('[CreateChunks] Calculating event chunks');

        // we are going to pull some amount of pages for every chunk. means we want to distribute (n1 / n2) chunks across the time period
        $departure_flights_per_chunk = intval(Config::get('flightaware.flights_per_page')) * intval(Config::get('flightaware.departure_pages_per_chunk'));
        $arrival_flights_per_chunk = intval(Config::get('flightaware.flights_per_page')) * intval(Config::get('flightaware.arrival_pages_per_chunk'));
        $flights_per_chunk = $departure_flights_per_chunk + $arrival_flights_per_chunk;

        $this->info('[CreateChunks] Chunk flight counts: D' . $departure_flights_per_chunk . ' A' . $arrival_flights_per_chunk . ' T' . $flights_per_chunk);

        $target_flights = intval(Config::get('flightaware.max_flights'));
        $optimal_chunk_len_seconds = floor($target_flights / $flights_per_chunk);
        $chunk_count = floor($duration / $optimal_chunk_len_seconds);

        $this->info('[CreateChunks] Optimal chunk length: ' . $optimal_chunk_len_seconds . 's, number of chunks: ' . $chunk_count);
        $this->info('[CreateChunks] Event will be ' . $chunk_count * $optimal_chunk_len_seconds . 's long');

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

        $this->info('[LoadChunks] Loading all data from FlightAware. Do not stop the script for any reason after this point!');
    }
}
