<?php

namespace App\Console\Commands;

use App\Class\LatLong;
use App\Mail\PilotPassportMail;
use App\PilotPassport;
use App\PilotPassportAirfield;
use App\PilotPassportAirfieldMap;
use App\PilotPassportAward;
use App\PilotPassportEnrollment;
use App\PilotPassportLog;
use App\RealopsPilot;
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
    private const SPEED_LIMIT = 30; // KTS GS

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
            if (PilotPassportEnrollment::where('cid', $p->cid)->get()->isEmpty()) {
                continue;
            }
            $this->info('Enrolled pilot found connected: ' . $p->cid);
            if ($p->groundspeed > SELF::SPEED_LIMIT) {
                $this->info('- [Speed Limit] - too fast to log');
                continue;
            }

            $ppos = new LatoLng($p->latitude, $p->longitude);
            $airports = PilotPassportAirfield::all();
            foreach ($airports as $a) {
                $this->info('- Checking ' . $a->id);
                if (!PilotPassport::airfieldInPilotChallenge($a->id, $p->cid)) {
                    $this->info('- [Enrollment Limit] - airfield not part of an enrolled challenge');
                    continue;
                }
                if ($p->altitude - $a->elevation > SELF::ALTITUDE_LIMIT) {
                    $this->info('- [Altitude Limit] - too high log');
                    continue;
                }
                if (LatLong::calcDistance($ppos, $a->fetchLatLong()) > SELF::RADIUS_LIMIT) {
                    $this->info('- [Radius Limit] - too far away to log');
                    continue;
                }
                if (!PilotPassportLog::where('cid', $p->cid)->where('airfield', $a->id)->get()->isEmpty()) {
                    $this->info('- [Visited Limit] - airfield has already been logged');
                    continue;
                }
                $this->info('- [At Airport Now] - logging the visit!');

                $passport = new PilotPassportLog;
                $passport->cid = $p->cid;
                $passport->airfield = $a->id;
                $passport->visited_on = date('Y-m-d H:i:s');
                $passport->callsign = $p->callsign;
                $passport->aircraft_type = (is_null($p->flight_plan)) ? '' : $p->flight_plan->aircraft_faa;
                $passport->save();

                $pilot = RealopsPilot::find($p->cid);
                Mail::to($pilot->email)->send(new PilotPassportMail('visited_airfield', $pilot, $a));
                $this->checkPhaseComplete($pilot);
                break;
            }
        }
    }

    public static function checkPhaseComplete(RealopsPilot $pilot) {
        $pilot_has_visited = PilotPassportLog::where('cid', $pilot->id)->orderBy('airfield', 'asc')->pluck('airfield')->toArray();
        $challenges = PilotPassport::get();
        foreach ($challenges as $c) {
            $challenge_airfields = PilotPassportAirfieldMap::where('mapped_to', $c->id)->orderBy('airfield', 'asc')->pluck('airfield')->toArray();
            $challenge_complete = true;
            foreach ($challenge_airfields as $c_a) {
                if (!in_array($c_a, $pilot_has_visited)) {
                    $challenge_complete = false;
                    break;
                }
            }
            if ($challenge_complete) {
                $award = new PilotPassportAward;
                $award->cid = $pilot->id;
                $award->challenge_id = $c->id;
                $award->awarded_on = date('Y-m-d H:i:s');
                $award->save();
            }
            Mail::to($pilot->email)->send(new PilotPassportMail('phase_complete', $pilot, $c));
        }
    }

    public function getStatsData() {
        $client = new Client();
        $res = $client->request('GET', $this->statusUrl);
        $data = json_decode($res->getBody());
        return $data;
    }
}
