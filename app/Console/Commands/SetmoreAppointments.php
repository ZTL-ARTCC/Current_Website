<?php

namespace App\Console\Commands;

use App\SetmoreAppointment;
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
    protected $signature = 'SetmoreAppintments:Update';

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
        $setmoreAppointments = [];
        $from = Carbon::now()->subHours(2)->toDateString();
        $to = Carbon::now()->addDays(90)->toDateString();
        $client = new Client();
        try {
            $res = $client->request('GET', Config::get('setmore.endpoint') . '/o/oauth2/token?refreshToken=' . Config::get('setmore.api_key'));
            $setmoreAuthResp = (string) $res->getBody();
            $setmoreAuthRespA = json_decode($setmoreAuthResp, true);
        } catch (ClientErrorResponseException $exception) {
            echo "Unable to fetch access token from Setmore API: $exception";
        }
        if (isset($setmoreAuthRespA) && isset($setmoreAuthRespA['response'])) {
            if ($setmoreAuthRespA['response'] && isset($setmoreAuthRespA['data']['token'])) {
                $setmoreAccessToken = $setmoreAuthRespA['data']['token']['access_token'];
            }
        }
        if ($setmoreAccessToken != null) {
            $setmoreAppointments = $this->getSetmoreAppointments($setmoreAccessToken, $from, $to);
            $setmoreServices = $this->getSetmoreServiceNames($setmoreAccessToken);
            $setmoreStaff = $this->getSetmoreStaffNames($setmoreAccessToken);
            DB::table('setmore')->truncate();
            foreach ($setmoreAppointments as $setmoreAppointment) {
                $appointment = new SetmoreAppointment;
                $appointment->setmore_key = $setmoreAppointment['key'];
                $appointment->start_time = $setmoreAppointment['start_time'];
                $appointment->duration = $setmoreAppointment['duration'];
                $appointment->staff_key = $setmoreAppointment['staff_key'];
                $appointment->staff_name = (isset($setmoreStaff[$setmoreAppointment['staff_key']])) ? $setmoreStaff[$setmoreAppointment['staff_key']] : null;
                $appointment->service_key = $setmoreAppointment['service_key'];
                $appointment->service_description = (isset($setmoreServices[$setmoreAppointment['service_key']])) ? $setmoreServices[$setmoreAppointment['service_key']] : null;
                $appointment->customer_cid = (isset($setmoreAppointment['customer']['additional_fields']['cid'])) ? $setmoreAppointment['customer']['additional_fields']['cid'] : null;
                $appointment->save();
            }
        }
    }

    private function getSetmoreAppointments($setmoreAccessToken, $from, $to) {
        $setmoreAppointments = [];
        $setmoreCursor = null;
        do {
            $appointmentBatch = $this->getAppointmentBatch($setmoreAccessToken, $from, $to, $setmoreCursor);
            if (is_array($appointmentBatch)) {
                $setmoreAppointments = array_merge($setmoreAppointments, $appointmentBatch);
            }
        } while (is_numeric($setmoreCursor));
        return $setmoreAppointments;
    }

    private function getAppointmentBatch($setmoreAccessToken, $from, $to, &$cursor) {
        $cursorStr = '';
        if (!is_null($cursor)) {
            $cursorStr = '&cursor=' . $cursor;
        }
        $fromDate = Carbon::createFromDate($from)->format('d-m-Y');
        $toDate = Carbon::createFromDate($to)->format('d-m-Y');
        $client = new Client();
        try {
            $res = $client->request(
                'GET',
                Config::get('setmore.endpoint') . '/bookingapi/appointments?startDate=' . $fromDate . '&endDate=' . $toDate . $cursorStr,
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
                $serviceNamesByToken[$service['key']] = $service['service_name'];
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
                $serviceNamesByToken[$staff['key']] = $staff['first_name'] . ' ' . $staff['last_name'];
            }
        }
        return $staffNamesByKey;
    }
}
