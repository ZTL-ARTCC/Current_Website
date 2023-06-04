<?php

namespace App\Console\Commands;

use App\SetmoreAppointment;
use App\SetmoreLoad;
use Carbon\Carbon;
use Config;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SetmoreAppointments extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetmoreAppointments:Update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches training appointments from Setmore.';

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
        $setmoreAccessToken = null;
        $setmoreAppointments = $setmoreServices = $setmoreStaff = [];
        $from = Carbon::now()->subHours(2)->toDateString();
        $to = Carbon::now()->addDays(90)->toDateString();
        $client = new Client();
        try {
            $res = $client->request('GET', Config::get('setmore.endpoint') . '/o/oauth2/token?refreshToken=' . Config::get('setmore.api_key'));
            $setmoreAuthResp = (string) $res->getBody();
            $setmoreAuthRespA = json_decode($setmoreAuthResp, true);
            $setmoreAccessToken = isset($setmoreAuthRespA['data']['token']) ? $setmoreAuthRespA['data']['token']['access_token'] : null;
        } catch (ClientErrorResponseException $exception) {
            echo "Unable to fetch access token from Setmore API: $exception";
            return 1;
        }
        if ($setmoreAccessToken != null) {
            $setmoreAppointments = $this->getSetmoreAppointments($setmoreAccessToken, $from, $to, true);
            $setmoreServices = $this->getSetmoreServiceNames($setmoreAccessToken);
            $setmoreStaff = $this->getSetmoreStaffNames($setmoreAccessToken);
            foreach ($setmoreAppointments as $setmoreAppointment) {
                $appointment = new SetmoreLoad;
                $appointment->setmore_key = $setmoreAppointment['key'];
                $start_time_string = substr(str_replace('T', ' ', $setmoreAppointment['start_time']), 0, -1) . ':00';
                $appointment->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $start_time_string);
                $appointment->duration = $setmoreAppointment['duration'];
                $appointment->staff_key = $setmoreAppointment['staff_key'];
                $appointment->staff_name = (isset($setmoreStaff[$setmoreAppointment['staff_key']])) ? $setmoreStaff[$setmoreAppointment['staff_key']] : null;
                $appointment->service_key = $setmoreAppointment['service_key'];
                $appointment->service_description = (isset($setmoreServices[$setmoreAppointment['service_key']])) ? $setmoreServices[$setmoreAppointment['service_key']] : null;
                $appointment->customer_cid = (isset($setmoreAppointment['customer']['additional_fields']['CID'])) ? $setmoreAppointment['customer']['additional_fields']['CID'] : null;
                $appointment->save();
            }
            if (DB::table('setmore_load')->count() == count($setmoreAppointments)) {
                DB::table('setmore')->truncate();
                $loadAppts = SetmoreLoad::get();
                foreach ($loadAppts as $loadAppt) {
                    $setmoreAppt = new SetmoreAppointment;
                    $setmoreAppt = $loadAppt->replicate();
                    $setmoreAppt->setTable('setmore');
                    $setmoreAppt->save();
                }
            }
            DB::table('setmore_load')->truncate();
        }
    }

    private function getSetmoreAppointments($setmoreAccessToken, $from, $to, $customerDetails=false) {
        $setmoreAppointments = [];
        $setmoreCursor = null;
        do {
            $appointmentBatch = $this->getAppointmentBatch($setmoreAccessToken, $from, $to, $setmoreCursor, $customerDetails);
            if (is_array($appointmentBatch)) {
                $setmoreAppointments = array_merge($setmoreAppointments, $appointmentBatch);
            }
        } while (is_numeric($setmoreCursor));
        return $setmoreAppointments;
    }

    private function getAppointmentBatch($setmoreAccessToken, $from, $to, &$cursor, $customerDetails=false) {
        $cursorStr = $customerDetailsStr = '';
        if (!is_null($cursor)) {
            $cursorStr = '&cursor=' . $cursor;
        }
        if ($customerDetails) {
            $customerDetailsStr = '&customerDetails=true';
        }
        $fromDate = Carbon::createFromDate($from)->format('d-m-Y');
        $toDate = Carbon::createFromDate($to)->format('d-m-Y');
        $client = new Client();
        try {
            $res = $client->request(
                'GET',
                Config::get('setmore.endpoint') . '/bookingapi/appointments?startDate=' . $fromDate . '&endDate=' . $toDate . $cursorStr . $customerDetailsStr,
                [
                    'headers' =>
                    [
                        'Content-type' => "application/json",
                        'Authorization' => "Bearer {$setmoreAccessToken}"
                    ]
                ]
            );
            $setmoreResp = (string) $res->getBody();
            $setmoreRespA = json_decode($setmoreResp, true);
        } catch (ClientErrorResponseException $exception) {
            echo "Unable to fetch appointments from Setmore API: $exception";
        }
        if (isset($setmoreRespA['data']['cursor'])) {
            $cursor = $setmoreRespA['data']['cursor'];
        } else {
            $cursor = null;
        }
        if (isset($setmoreRespA['data']['appointments'])) {
            return $setmoreRespA['data']['appointments'];
        }
        return null;
    }

    private function getSetmoreServiceNames($setmoreAccessToken) {
        $serviceNamesByKey = [];
        $client = new Client();
        try {
            $res = $client->request(
                'GET',
                Config::get('setmore.endpoint') . '/bookingapi/services',
                [
                    'headers' =>
                    [
                        'Content-type' => "application/json",
                        'Authorization' => "Bearer {$setmoreAccessToken}"
                    ]
                ]
            );
            $setmoreResp = (string) $res->getBody();
            $setmoreRespA = json_decode($setmoreResp, true);
        } catch (ClientErrorResponseException $exception) {
            echo "Unable to fetch services from Setmore API: $exception";
        }
        if (isset($setmoreRespA)) {
            foreach ($setmoreRespA['data']['services'] as $service) {
                $serviceNamesByKey[$service['key']] = $service['service_name'];
            }
        }
        return $serviceNamesByKey;
    }

    private function getSetmoreStaffNames($setmoreAccessToken) {
        $cursorStr = null;
        $staffNamesByKey = $setmoreRespA = $setmoreRespTmp = [];
        $client = new Client();
        do {
            try {
                $res = $client->request(
                    'GET',
                    Config::get('setmore.endpoint') . '/bookingapi/staffs',
                    [
                        'headers' =>
                        [
                            'Content-type' => "application/json",
                            'Authorization' => "Bearer {$setmoreAccessToken}"
                        ]
                    ]
                );
                $setmoreResp = (string) $res->getBody();
                $setmoreRespTmp = json_decode($setmoreResp, true);
                $setmoreRespA = array_merge($setmoreRespA, $setmoreRespTmp['data']['staffs']);
                $cursorStr = (array_key_exists('cursor', $setmoreRespTmp)) ? $cursorStr = $setmoreRespTmp['cursor'] : null;
            } catch (ClientErrorResponseException $exception) {
                echo "Unable to fetch staff from Setmore API: $exception";
            }
        } while ((count($setmoreRespTmp) == 50) && !is_null($cursorStr));
        if (isset($setmoreRespA)) {
            foreach ($setmoreRespA as $staff) {
                $staffNamesByKey[$staff['key']] = $staff['first_name'] . ' ' . $staff['last_name'];
            }
        }
        return $staffNamesByKey;
    }
}
