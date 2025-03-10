<?php

namespace App\Console\Commands;

use App\Event;
use App\EventRegistration;
use App\EventStat;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class EventStatReport extends Command {
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
    public function handle() {
        $events = Event::doesnthave('eventStat')->whereNotNull('tracking_airports')->get();

        foreach ($events as $event) {
            $event_length = (explode(':', $event->end_time)[0] - explode(':', $event->start_time)[0]) % 24;
            $event_length = $event_length < 0 ? $event_length + 24 : $event_length;
            $event_start_time = Carbon::create($event->date . ' ' . $event->start_time);
            $event_end_time = Carbon::create($event_start_time)->addHours($event_length);

            if (! $event_end_time->isPast()) {
                continue;
            }

            $controller_count = $this->generateEmptyControllerCount();
            $controllers = EventRegistration::where('event_id', $event->id)
                                            ->where('status', EventRegistration::STATUSES['ASSIGNED'])
                                            ->where('no_show', false)
                                            ->get()
                                            ->map(function ($registration) {
                                                return User::find($registration->controller_id);
                                            })
                                            ->unique();

            $controllers->each(function ($controller) use (&$controller_count) {
                $controller_count[$controller->rating_short]++;
            });

            $client = new Client();

            $airport_data = collect(explode(',', $event->tracking_airports))
            ->map(function ($airport) {
                return trim($airport);
            })
            ->mapWithKeys(function ($airport) use ($client, $event_start_time, $event_end_time) {
                $data = [];
                $res = $client->request('GET', 'https://statsim.net/flights/airport/?icao=K' . $airport . '&period=custom&json=true&from=' . $event_start_time . '&to=' . $event_end_time);
                $result = json_decode($res->getBody());

                $data["departures"] = count($result->departed);
                $data["arrivals"] = count($result->arrived);

                return [$airport => $data];
            });

            $event_stat = new EventStat;
            $event_stat->event_id = $event->id;
            $event_stat->controllers_by_rating = collect($controller_count);
            $event_stat->movements = $airport_data;
            $event_stat->save();
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
            'I3' => 0,
            'SUP' => 0,
            'ADM' => 0
        ];
    }
}
