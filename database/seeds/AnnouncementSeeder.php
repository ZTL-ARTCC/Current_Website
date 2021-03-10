<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://api.vatusa.net/v2/facility/'.Config::get('vatusa.facility'));
        $result = json_decode($res->getBody())->role;
        foreach($result->data as $r) {
            if($r->role == 'ATM') {
                $atm = $r->cid;
            }
        }
        if(isset($atm) == false) {
            foreach($result->data as $r) {
                if($r->role == 'DATM') {
                    $atm = $r->cid;
                }
            }
            if(isset($atm) == false) {
                foreach($result->data as $r) {
                    if($r->role == 'TA') {
                        $atm = $r->cid;
                    }
                }
                if(isset($atm) == false) {
                    foreach($result->data as $r) {
                        if($r->role == 'WM') {
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
