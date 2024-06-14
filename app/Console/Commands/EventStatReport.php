<?php

namespace App\Console\Commands;

use App\Event;
use Illuminate\Console\Command;

class EventStatReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Events:GenerateStatReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates stat reports for events in the past';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::doesnthave('eventStat')->get();

        foreach ($events as $event) {
            $controller_count = $this->generateEmptyControllerCount();
           
        }
    }

    private function generateEmptyControllerCount() {
        return [
            'S1' => 0,
            'S2' => 0,
            'S3' => 0,
            'C1' => 0,
            'C3' => 0,
            'I1' => 0,
            'I3' => 0
        ];
    }
}
