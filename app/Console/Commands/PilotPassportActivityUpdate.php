<?php

namespace App\Console\Commands;

use App\Class\LatLng;
use App\Mail\PilotPassportMail;
use App\PilotPassport;
use App\PilotPassportAirfield;
use App\PilotPassportAirfieldMap;
use App\PilotPassportAward;
use App\PilotPassportEnrollment;
use App\PilotPassportLog;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Mail;

class PilotPassportActivityUpdate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PilotPassport:ActivityUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks VATSIM datafeed and logs accomplishment of pilot passport activities.';

    protected $statusUrl = "https://data.vatsim.net/v3/vatsim-data.json";

    // Parameters to consider an aircraft 'at' an airfield
    private const RADIUS_LIMIT = 3; // NM
    private const ALTITUDE_LIMIT = 500; // ft
    private const SPEED_LIMIT = 50; // KTS GS

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
        $statsData = $this->getStatsData();
        
        foreach ($statsData->pilots as $p) {
            if (!PilotPassportEnrollment::where('cid', $p->cid)->get()) {
                continue;
            }
            if ($p->groundspeed > SELF::SPEED_LIMIT) {
                continue;
            }

            $ppos = new LatLng($p->latitude, $p->longitude);
            $airports = PilotPassportAirfield::get();
            foreach ($airports as $a) {
                if ($p->altitude - $a->elevation > SELF::ALTITUDE_LIMIT) {
                    continue;
                }
                if ($this->calcDistance($ppos, $a->fetchLatLng()) > SELF::RADIUS_LIMIT) {
                    continue;
                }
                if (!PilotPassport::airfieldInPilotChallenge($a->icao_id, $p->cid)) {
                    continue;
                }

                $passport = new PilotPassportLog;
                $passport->cid = $p->cid;
                $passport->airfield = $a->icao_id;
                $passport->visited_on = date('Y-m-d H:i:s');
                $passport->callsign = $p->callsign;
                $passport->aircraft_type = (property_exists($p, 'flight_plan')) ? $p->flight_plan->aircraft_faa : null;
                dd($passport);
                $passport->save();
                // Send congratulatory email
                $pilot = PilotPassportEnrollment::find($p->cid);
                Mail::to($pilot->email)->send(new PilotPassportMail('visited_airfield', $pilot, $a));
                $this->checkPhaseComplete($p);
                break;
            }
        }
    }

    public static function checkPhaseComplete(RealopsPilot $pilot): void {
        $pilot_has_visited = PilotPassportLog::where('cid', $pilot->cid)->orderBy('airfield', 'asc')->pluck('airfield');
        $challenges = PilotPassport::get();
        foreach ($challenges as $c) {
            $challenge_airfields = PilotPassportAirfieldMap::where('mapped_to', $c->id)->orderBy('airfield', 'asc')->pluck('airfield');
            $challenge_complete = true;
            foreach ($challenge_airfields as $c_a) {
                if (!in_array($c_a, $pilot_has_visited)) {
                    $challenge_complete = false;
                    break;
                }
            }
            if ($challenge_complete) {
                $award = new PilotPassportAward;
                $award->cid = $pilot->cid;
                $award->challenge_id = $c->id;
                $award->awarded_on = date('Y-m-d H:i:s');
                $award->save();
            }
        }
        Mail::to($pilot->email)->send(new PilotPassportMail('phase_complete', $pilot, $c));
    }

    public function getStatsData() {
        $client = new Client();
        $res = $client->request('GET', $this->statusUrl);
        $data = json_decode($res->getBody());
        return $data;
    }

    public static function calcDistance($point1, $point2) {
        $dist = acos(sin($point1->radLatitude()) * sin($point2->radLatitude()) + cos($point1->radLatitude())
            * cos($point2->radLatitude()) * cos($point1->radLongitude() - $point2->radLongitude()));
        return (((180 * 60) / pi()) * $dist);
    }
}
