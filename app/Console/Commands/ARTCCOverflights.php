<?php

namespace App\Console\Commands;

use App\Overflight;
use App\OverflightUpdate;
use Config;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use SimpleXMLElement;

class ARTCCOverflights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Overflights:GetOverflights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the overflights for the specified ARTCC.';

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
        $client = new Client();
        $res = $client->get('https://api.denartcc.org/live/'.Config::get('vatusa.facility'));

        DB::table('flights_within_artcc')->truncate();

        $result = json_decode($res->getBody());
        foreach($result as $r) {
            $response = $client->request('GET', 'https://cert.vatsim.net/vatsimnet/idstatus.php?cid='.$r->cid);
            $res = new SimpleXMLElement($response->getBody());
            $pilot_name = $res->user->name_first.' '.$res->user->name_last;
            $flight = new Overflight;
            $flight->pilot_cid = $r->cid;
            $flight->pilot_name = $pilot_name;
            $flight->callsign = $r->callsign;
            $flight->type = $r->type;
            $flight->dep = $r->dep;
            $flight->arr = $r->arr;
            $flight->route = $r->route;
            $flight->save();
        }

        $update = OverflightUpdate::first();
        $update->delete();
        $u = new OverflightUpdate;
        $u->save();
    }
}
