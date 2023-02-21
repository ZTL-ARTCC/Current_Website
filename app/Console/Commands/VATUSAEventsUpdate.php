<?php

namespace App\Console\Commands;

use App\Event;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class VATUSAEventsUpdate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'VATUSAEvents:Update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches events from VATUSA and syncs id_topics in the events table.';

    protected $apiEndpoint = "https://api.vatusa.net/v2/public/events/";

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
        $vatusa_events = [];
        $vatusa_json = $this->getEvents();
        if (!is_array($vatusa_json)) {
            return;
        }
        foreach ($vatusa_json as $vatusa_item) {
            $vatusa_events[$vatusa_item['id_topic']] = $vatusa_item['title'];
        }
        $events = Event::fetchVisibleEvents();
        foreach ($events as $e) {
            if (in_array($e->name, $vatusa_events)) {
                $e->id_topic = array_search($e->name, $vatusa_events);
            } else {
                $e->id_topic = null;
            }
            $e->save();
        }
    }

    public function getEvents() {
        $client = new Client();
        $res = $client->request('GET', $this->apiEndpoint . '100', ['http_errors' => false]);
        $data = json_decode($res->getBody(), true);
        if (isset($data['data'])) {
            $data = $data['data'];
        }
        return $data;
    }
}
