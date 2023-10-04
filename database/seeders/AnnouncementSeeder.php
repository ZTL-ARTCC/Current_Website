<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $client = new Client();
        $res = $client->request('GET', Config::get('vatusa.base').'/v2/facility/'.Config::get('vatusa.facility'));
        $result = json_decode($res->getBody())->data->facility->roles;
        foreach ($result as $r) {
            if ($r->role == 'ATM') {
                $atm = $r->cid;
            }
        }
        if (isset($atm) == false) {
            foreach ($result as $r) {
                if ($r->role == 'DATM') {
                    $atm = $r->cid;
                }
            }
            if (isset($atm) == false) {
                foreach ($result as $r) {
                    if ($r->role == 'TA') {
                        $atm = $r->cid;
                    }
                }
                if (isset($atm) == false) {
                    foreach ($result as $r) {
                        if ($r->role == 'WM') {
                            $atm = $r->cid;
                        }
                    }
                }
            }
        }

        DB::table('announcement')->insert([
             'id' => 1,
             'body' => null,
             'staff_member' => $atm,
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now()
         ]);
    }
}
