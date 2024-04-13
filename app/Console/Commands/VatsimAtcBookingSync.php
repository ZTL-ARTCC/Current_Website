<?php

namespace App\Console\Commands;

use App\AtcBooking;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class VatsimAtcBookingSync extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Vatsim:AtcBookingSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync ATC Bookings with VATSIM API';

    /**
     * Execute the console command.
     */
    public function handle() {
        $bookings = AtcBooking::whereNull('vatsim_id')->whereDate('start', '>=', Carbon::today())->get();
        $client = new Client(['headers' => ['Authorization' => 'Bearer ' . Config::get('vatsim.booking_api_key'), 'Content-Type' => 'application/json']]);
        $uri = 'https://atc-bookings.vatsim.net/api/booking';

        foreach ($bookings as $b) {
            $res = $client->request('POST', $uri, ['body' => $b->toJson(), 'http_errors' => false]);

            if ($res->getStatusCode() == 201) {
                $res_body = json_decode($res->getBody());
                $b->vatsim_id = $res_body->id;
                $b->save();
            }
        }

        $res = $client->request('GET', $uri, ['query' => ['key_only' => true]]);
        $vatsim_bookings = json_decode($res->getBody());

        foreach ($vatsim_bookings as $b) {
            $id = $b->id;
            $result = AtcBooking::where('vatsim_id', $id)->first();
            if (! $result) {
                $client->request('DELETE', $uri . '/' . $id);
            }
        }
    }
}
