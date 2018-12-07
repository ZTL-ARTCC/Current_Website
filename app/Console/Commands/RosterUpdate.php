<?php

namespace App\Console\Commands;

use App\EventRegistration;
use App\User;
use Config;
use Eloquent\Collection;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent;
use Illuminate\Http\Request;

class RosterUpdate extends Command
{
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
        $res = $client->get('https://api.vatusa.net/v2/facility/'.Config::get('vatusa.facility').'/roster?apikey='.Config::get('vatusa.api_key'));
        $roster = json_decode($res->getBody());
        $users = User::where('visitor', '0')->where('status', '1')->get()->pluck('id');

        foreach($roster as $r) {
            if(User::find($r->cid) !== null) {
                $user = User::find($r->cid);
                $user->fname = $r->fname;
                $user->lname = $r->lname;
                $user->email = $r->email;
                $user->rating_id = $r->rating;
                $user->visitor = '0';
                $user->added_to_facility = substr($r->facility_join, 0, 10).' '.substr($r->facility_join, 11, 8);
                $user->save();
            } else {
                $user = new User;
                $user->id = $r->cid;
                $user->fname = $r->fname;
                $user->lname = $r->lname;
                $user->email = $r->email;
                $user->rating_id = $r->rating;
                if($r->rating == 2) {
                    $user->del = 1;
                    $user->gnd = 1;
                } elseif($r->rating == 3) {
                    $user->del = 1;
                    $user->gnd = 1;
                    $user->twr = 1;
                } elseif($r->rating == 4 || $r->rating == 5 || $r->rating == 7 || $r->rating == 8 || $r->rating == 10) {
                    $user->del = 1;
                    $user->gnd = 1;
                    $user->twr = 1;
                    $user->app = 1;
                }
                $user->visitor = '0';
                $user->status = '1';
                $user->added_to_facility = substr($r->facility_join, 0, 10).' '.substr($r->facility_join, 11, 8);
                $user->save();

                //Assigns controller initials
                $user = User::find($r->cid);

                $users_inc_v = User::get();
                $fn_initial = substr($user->fname, 0, 1);
                $ln_initial = substr($user->lname, 0, 1);

                a:
                $initials = $fn_initial.$ln_initial;
                $yes = 1;
                foreach($users_inc_v as $u) {
                    if($u->initials == $initials) {
                        $yes = 0;
                        break;
                    } else {
                        $yes = 1;
                    }
                }

                if($yes === 1) {
                    $user->initials = $initials;
                    $user->save();
                } else {
                    $fi_int = rand(1, 26);
                    $li_int = rand(1, 26);

                    if($fi_int == 1) {
                        $fn_initial = 'A';
                    } elseif($fi_int == 2) {
                        $fn_initial = 'B';
                    } elseif($fi_int == 3) {
                        $fn_initial = 'C';
                    } elseif($fi_int == 4) {
                        $fn_initial = 'D';
                    } elseif($fi_int == 5) {
                        $fn_initial = 'E';
                    } elseif($fi_int == 6) {
                        $fn_initial = 'F';
                    } elseif($fi_int == 7) {
                        $fn_initial = 'G';
                    } elseif($fi_int == 8) {
                        $fn_initial = 'H';
                    } elseif($fi_int == 9) {
                        $fn_initial = 'I';
                    } elseif($fi_int == 10) {
                        $fn_initial = 'J';
                    } elseif($fi_int == 11) {
                        $fn_initial = 'K';
                    } elseif($fi_int == 12) {
                        $fn_initial = 'L';
                    } elseif($fi_int == 13) {
                        $fn_initial = 'M';
                    } elseif($fi_int == 14) {
                        $fn_initial = 'N';
                    } elseif($fi_int == 15) {
                        $fn_initial = 'O';
                    } elseif($fi_int == 16) {
                        $fn_initial = 'P';
                    } elseif($fi_int == 17) {
                        $fn_initial = 'Q';
                    } elseif($fi_int == 18) {
                        $fn_initial = 'R';
                    } elseif($fi_int == 19) {
                        $fn_initial = 'S';
                    } elseif($fi_int == 20) {
                        $fn_initial = 'T';
                    } elseif($fi_int == 21) {
                        $fn_initial = 'U';
                    } elseif($fi_int == 22) {
                        $fn_initial = 'V';
                    } elseif($fi_int == 23) {
                        $fn_initial = 'W';
                    } elseif($fi_int == 24) {
                        $fn_initial = 'X';
                    } elseif($fi_int == 25) {
                        $fn_initial = 'Y';
                    } elseif($fi_int == 26) {
                        $fn_initial = 'Z';
                    }

                    if($li_int == 1) {
                        $ln_initial = 'A';
                    } elseif($li_int == 2) {
                        $ln_initial = 'B';
                    } elseif($li_int == 3) {
                        $ln_initial = 'C';
                    } elseif($li_int == 4) {
                        $ln_initial = 'D';
                    } elseif($li_int == 5) {
                        $ln_initial = 'E';
                    } elseif($li_int == 6) {
                        $ln_initial = 'F';
                    } elseif($li_int == 7) {
                        $ln_initial = 'G';
                    } elseif($li_int == 8) {
                        $ln_initial = 'H';
                    } elseif($li_int == 9) {
                        $ln_initial = 'I';
                    } elseif($li_int == 10) {
                        $ln_initial = 'J';
                    } elseif($li_int == 11) {
                        $ln_initial = 'K';
                    } elseif($li_int == 12) {
                        $ln_initial = 'L';
                    } elseif($li_int == 13) {
                        $ln_initial = 'M';
                    } elseif($li_int == 14) {
                        $ln_initial = 'N';
                    } elseif($li_int == 15) {
                        $ln_initial = 'O';
                    } elseif($li_int == 16) {
                        $ln_initial = 'P';
                    } elseif($li_int == 17) {
                        $ln_initial = 'Q';
                    } elseif($li_int == 18) {
                        $ln_initial = 'R';
                    } elseif($li_int == 19) {
                        $ln_initial = 'S';
                    } elseif($li_int == 20) {
                        $ln_initial = 'T';
                    } elseif($li_int == 21) {
                        $ln_initial = 'U';
                    } elseif($li_int == 22) {
                        $ln_initial = 'V';
                    } elseif($li_int == 23) {
                        $ln_initial = 'W';
                    } elseif($li_int == 24) {
                        $ln_initial = 'X';
                    } elseif($li_int == 25) {
                        $ln_initial = 'Y';
                    } elseif($li_int == 26) {
                        $ln_initial = 'Z';
                    }

                    goto a;
                }
            }
        }

        foreach($users as $u) {
            $delete = 0;
            foreach($roster as $r) {
                $id = $r->cid;
                if($u == $id) {
                    $delete = 1;
                }
            }
            if($delete == '0') {
                $use = User::find($u);
                if($use->visitor == 0 && $use->api_exempt == 0) {
                    $event_requests = EventRegistration::where('controller_id', $use->id)->get();
                    foreach($event_requests as $e) {
                        $e->delete();
                    }
                    $use->delete();
                }
            }
        }
    }
}
