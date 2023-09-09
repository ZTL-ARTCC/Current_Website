<?php

namespace App\Console\Commands;

use App\Event;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SupportEvents extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Events:UpdateSupportEvents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates any neighbor support events that aren\'t already in the DB.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    protected array $pull_events_for = [
        'all_events' => ['KZID', 'KZDC', 'KZJX', 'KZHU', 'KZME'],
        'fno_or_live_only' => ['KZMA', 'KZNY']
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->info("-- Updating support events - this may take a while --");

        $client = new Client();

        $this->info('Downloading event info from VATSIM');

        $res = $client->get('https://my.vatsim.net/api/v1/events/all');

        $this->info('Parsing event info JSON');

        $result = json_decode($res->getBody());
        if (is_null($result)) {
            return;
        }

        foreach ($result->data as $event) {
            $existing = Event::where('vatsim_id', $event->id)->first();
            if ($existing !== null) {
                $this->info("Skipping already processed event ".$event->id);
                continue;
            } // this event has already been processed

            $pull_this_event = false;
            $organizer = null;

            // parse times
            $start_time = Carbon::parse($event->start_time);
            $end_time = Carbon::parse($event->end_time);

            foreach ($event->organisers as $o) {
                if (in_array($o->region, $this->pull_events_for['all_events'])) {
                    $pull_this_event = true;
                    $organizer = substr($o->region, 1);
                    break;
                }
                if (in_array($o->region, $this->pull_events_for['fno_or_live_only'])) {
                    // is it a live event?
                    if (str_contains(strtolower($event->name), 'live')) {
                        // live event - pull this event too
                        $pull_this_event = true;
                        $organizer = substr($o->region, 1);
                        break;
                    }
                    // is it on a friday? (FNO)
                    if ($start_time->dayOfWeek == 6) { // 06: Friday
                        // FNO - pull this event too
                        $pull_this_event = true;
                        $organizer = substr($o->region, 1);
                        break;
                    }
                }
            }

            if (!$pull_this_event) {
                continue;
            }

            $this->info('Creating support event with vatsim id '.$event->id);

            // download the event banner
            // public/storage/event_banners/vatsim_ID.ext

            $this->info($event->id.': Downloading banner');

            $public_url = '/event_banners/vatsim_'.$event->id.substr($event->banner, -4);

            Storage::disk('public')->put($public_url, file_get_contents($event->banner));

            // create the event in our database

            $this->info($event->id.': Saving to database');

            $our_event = new Event;
            $our_event->name = $event->name;
            $our_event->host = $organizer;
            $our_event->description = $event->description;
            $our_event->date = $start_time->toDate();
            $our_event->start_time = $start_time->toTimeString('minute');
            $our_event->end_time = $end_time->toTimeString('minute');

            $our_event->banner_path = $public_url;
            $our_event->reduceEventBanner();

            $our_event->banner_path = '/storage/'.$public_url; // TODO, we need to download this
            $our_event->status = 0;
            $our_event->reg = 0;
            $our_event->type = 2; // auto - unverified
            $our_event->vatsim_id = $event->id;
            $our_event->save();

            $this->info('Created ' . $event->id);
        }
    }
}
