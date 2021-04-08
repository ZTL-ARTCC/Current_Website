<?php

namespace App\Console\Commands;

use App\ATC;
use App\ControllerLog;
use App\ControllerLogUpdate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use DB;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class OnlineControllerUpdate extends Command
{
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
		$statsData = $this->getStatsData();
		$last_update_log = ControllerLogUpdate::get()->first();
		$last_update = $last_update_log->created_at;
		$last_update_log->delete();
		$update_now = new ControllerLogUpdate;
		$update_now->save();

		DB::table('online_atc')->truncate();

		
		foreach ($statsData->controllers as $line)
		{
		
				$position = $line->callsign;
                $cid = $line->cid;
                $name = $line->name;
                $frequency = $line->frequency;
                $time_logon = $line->logon_time;

				foreach ($this->facilities as $facility)
				{
					$is_controller = strpos($position, $facility) === 0;
					if ($is_controller) break;
				}


				if ($is_controller) {
					// Found an ATC user
                    $time_logon = substr($time_logon, 0, -1);
                    $time_logon = str_replace('T',' ',$time_logon);
                    $time_logon = explode(".",$time_logon);
                    $time_logon = $time_logon[0];
                    $time_logon = Carbon::createFromFormat('Y-m-d H:i:s', $time_logon)->timestamp;
					$time_now = strtotime(Carbon::now());
					$duration = $time_now - $time_logon;

					ATC::create([
						'position' => $position,
						'freq' => $frequency,
						'name' => $name,
						'cid' => $cid,
						'time_logon' => $time_logon,
					]);


					// Is this neccessary? It detects if the streamupdate of the last record for the user matches this one
					// Shouldn't bog anything down unless we are running this too often
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
					}
					else
					{
						$MostRecentLog->duration = $duration;
						$MostRecentLog->streamupdate = strtotime($update_now->created_at);
						$MostRecentLog->save();
					}
				}
			

			
		}
		$time = Carbon::now('Etc/UTC')->format('H:i').'Z';
    }

    public function getStatsData() {
        $client = new Client();
        $res = $client->request('GET', $this->statusUrl);

        $data = json_decode($res->getBody());


		return $data;
    }
}
