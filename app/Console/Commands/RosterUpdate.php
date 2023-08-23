<?php

namespace App\Console\Commands;

use App\EventRegistration;
use App\User;
use Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RosterUpdate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RosterUpdate:UpdateRoster';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the roster against the VATUSA roster.';

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
        $client = new Client();
        $res = $client->get('https://api.vatusa.net/v2/facility/'.Config::get('vatusa.facility').'/roster?apikey='.Config::get('vatusa.api_key'));

        if ($res->getStatusCode() == "200") {
            $roster = json_decode($res->getBody());
        } else {
            exit(1);
        }

        foreach ($roster->data as $r) {
            $user = User::find($r->cid);

            if ($user == null) {
                $user = new User();
                $user->id = $r->cid;

                if ($r->rating == 2) {
                    $user->del = 1;
                    $user->gnd = 1;
                } elseif ($r->rating == 3) {
                    $user->del = 1;
                    $user->gnd = 1;
                    $user->twr = 1;
                } elseif ($r->rating == 4 || $r->rating == 5 || $r->rating == 7 || $r->rating == 8 || $r->rating == 10) {
                    $user->del = 1;
                    $user->gnd = 1;
                    $user->twr = 1;
                    $user->app = 1;
                }
            }

            $user->fname = $r->fname;
            $user->lname = $r->lname;
            if ($user->initials == null) {
                $user->initials = User::generateControllerInitials($user->fname, $user->lname);
            }
            $user->email = $r->email;
            $user->rating_id = $r->rating;
            $user->visitor = 0;
            $user->status = 1;
            $user->added_to_facility = substr($r->facility_join, 0, 10) . ' ' . substr($r->facility_join, 11, 8);

            $user->save();
        }

        $users = User::where('visitor', 0)->where('status', 1)->where('api_exempt', 0)->get();
        foreach ($users as $u) {
            $delete = true;
            foreach ($roster->data as $r) {
                if ($u->id == $r->cid) {
                    $delete = false;
                    break;
                }
            }

            if ($delete) {
                $event_requests = EventRegistration::where('controller_id', $u->id)->get();

                foreach ($event_requests as $e) {
                    $e->delete();
                }

                $u->status = 2;
                $u->save();
            }
        }

        $users = User::where('status', 2)->get();
        foreach ($users as $u) {
            $u->initials = null;
            $u->save();
        }
    }
}
