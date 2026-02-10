<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpdateFAA extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FAA:update-faa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates FAA-specific datafiles';

    const FAA_DATA_URL = 'https://www.fly.faa.gov/rmt/data_file/';
    private $routes = [
        [
            'name' => 'PRD',
            'model' => 'App\PreferredRoute',
            'file' => 'prefroutes_db.csv'
        ]
    ];
    private ProgressIndicator $progressIndicator;
    private $path;

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->info('Updating FAA-specific datafiles');
        $this->path = Storage::disk('local')->path('private/');
        $this->setup_progress_indicator();
        $this->update_routes();
    }

    private function update_routes(): void {
        foreach ($this->routes as $route) {
            $route = (object) $route;
            $this->progressIndicator->start("Fetching $route->name info from FAA server...");
            $this->call_api(SELF::FAA_DATA_URL . $route->file, $this->path, strtolower($route->name) . '_new.csv');
            $this->progressIndicator->finish("Fetching $route->name info from FAA server... done");

            // Update table
            $this->progressIndicator->start("Updating $route->name table...");
            if (($handle = fopen($this->path . strtolower($route->name) . '_new.csv', "r")) !== false) {
                $route->model::truncate();
                while (($rte_line = fgetcsv($handle, null, ",")) !== false) {
                    if (!is_array($rte_line)) {
                        continue;
                    }
                    $bom = "\xef\xbb\xbf";
                    if (substr($rte_line[0], 0, 3) === $bom) {
                        $rte_line[0] = substr($rte_line[0], 3);
                    }
                    if ($rte_line[0] == 'RCode' || $rte_line[0] == 'Orig') { // It's the header line, skip it
                        continue;
                    }
                    if ($route->name == 'CDR') {
                        $load_arr = [
                            'route_code' => $rte_line[0],
                            'origin' => $rte_line[1],
                            'destination' => $rte_line[2],
                            'departure_fix' => substr($rte_line[3], 0, 5), // Enforce 5 characters
                            'route_string' => $rte_line[4],
                            'departure_center' => $rte_line[5],
                            'arrival_center' => $rte_line[6],
                            'transit_centers' => $rte_line[7],
                            'coordination_required' => $rte_line[8],
                            'play' => $rte_line[9],
                            'navigation_equipment' => $rte_line[10]
                        ];
                    } else {
                        $load_arr = [
                            'orig' => $rte_line[0],
                            'route_string' => $rte_line[1],
                            'dest' => $rte_line[2],
                            'hours1' => $rte_line[3],
                            'hours2' => $rte_line[4],
                            'hours3' => $rte_line[5],
                            'type' => $rte_line[6],
                            'area' => $rte_line[7],
                            'altitude' => (is_numeric($rte_line[8])) ? $rte_line[8] : null,
                            'aircraft' => $rte_line[9],
                            'direction' => $rte_line[10],
                            'seq' => $rte_line[11],
                            'dcntr' => $rte_line[12],
                            'acntr' => $rte_line[13]
                        ];
                    }
                    $route->model::create($load_arr);
                    $this->progressIndicator->advance();
                }
                fclose($handle);
            }
            $this->progressIndicator->finish("Updating $route->name table... done");

            // Remove, rename file
            $this->info("Removing unused local $route->name files");
            if (file_exists($this->path . $route->name . '.csv')) {
                unlink($this->path . $route->name . '.csv');
                $this->info("- Deleted $route->name.csv");
            }
            if (file_exists($this->path . $route->name . '_new.csv')) {
                rename($this->path . $route->name . '_new.csv', $this->path . $route->name . '.csv');
            }
            $this->info("$route->name file cleanup complete");
        }
    }

    private function call_api($url, $path = null, $write_file = null): string|null {
        $progress = $this->progressIndicator;
        $write_to_filename = (is_null($write_file)) ? basename($url) : $write_file;
        $client = new Client();
        $response = $client->get($url, [
            'config' => [
                'curl' => [
                    'CURLOPT_RETURNTRANSFER' => true,
                    'CURLOPT_CONNECTTIMEOUT' => 3,
                    'CURLOPT_ENCODING' => 'gzip',
                    'CURLOPT_SSL_VERIFYPEER' => false
                ]
            ],
            'progress' => function () use ($progress) {
                $progress->advance();
            },
            'sink' => $path . $write_to_filename
        ]);
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        }
        return null;
    }

    private function setup_progress_indicator(): void {
        $output = new ConsoleOutput;
        $this->progressIndicator = new ProgressIndicator($output);
    }
}
