<?php

namespace App\Console\Commands;

use App\ATC;
use App\ControllerLog;
use App\ControllerLogUpdate;
use App\User;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class OnlineControllerUpdate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OnlineControllers:GetControllers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves all the online controllers and records them in the database.';

    protected $statusUrl = "https://data.vatsim.net/v3/vatsim-data.json";

    protected $facilities = [
        /* BRAVO */
        'ATL','CLT',
        /* CHARLIE */
        'BHM','GSP','AVL','GSO','TYS','CHA','FTY','RYY','AHN',
        /* DELTA */
        'AGS','GMU','GYH','TCL','MXF','MGM','LSF','CSG','MCN','WRB','JQF','VUJ','INT','TRI','LZU','ASN','HKY','PDK',
        /* OBSERVER */
        'ZTL'
    ];

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
        $last_update_log = ControllerLogUpdate::get()->first();
        $last_update_log->delete();
        $update_now = new ControllerLogUpdate;
        $update_now->save();

        DB::table('online_atc')->truncate();

        
        foreach ($statsData->controllers as $line) {
            $position = $line->callsign;
            $cid = $line->cid;
            $name = $line->name;
            $frequency = $line->frequency;
            $time_logon = $line->logon_time;

            $user = User::find($cid);
            if ($user) {
                $name = $user->fname . ' ' . substr($user->lname, 0, 1) . '.';
            }

            foreach ($this->facilities as $facility) {
                $is_controller = strpos($position, $facility . "_") === 0;
                if ($is_controller) {
                    break;
                }
            }

            if ($is_controller) {
                if (preg_match('/(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})/', $time_logon, $time_match)) {
                    $time_logon_str = str_replace('T', ' ', $time_match[0]);
                    $time_logon = Carbon::createFromFormat('Y-m-d H:i:s', $time_logon_str)->timestamp;
                } else {
                    $time_logon = Carbon::now('UTC')->timestamp;
                }
                $time_now = Carbon::now('UTC')->timestamp;
                $duration = $time_now - $time_logon;

                ATC::create([
                    'position' => $position,
                    'freq' => $frequency,
                    'name' => $name,
                    'cid' => $cid,
                    'time_logon' => $time_logon,
                ]);

                $MostRecentLog = ControllerLog::where('cid', $cid)->orderBy('time_logon', 'DESC')->first();

                if (!$MostRecentLog || $MostRecentLog->time_logon != $time_logon) {
                    ControllerLog::create([
                        'cid' => $cid,
                        'name' => $name,
                        'position' => $position,
                        'duration' => $duration,
                        'date' => date('n/j/y'),
                        'time_logon' => $time_logon,
                        'streamupdate' => strtotime($update_now->created_at),
                    ]);
                } else {
                    $MostRecentLog->duration = $duration;
                    $MostRecentLog->streamupdate = strtotime($update_now->created_at);
                    $MostRecentLog->save();
                }
            }
        }
    }

    public function getStatsData() {
        $client = new Client();
        $res = $client->request('GET', $this->statusUrl);

        $data = json_decode($res->getBody());

        return $data;
    }
}
