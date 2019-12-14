<?php

namespace App\Console\Commands;

use App\EventRegistration;
use App\User;
use App\VisitRej;
use Config;
use DB;
use Eloquent\Collection;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent;
use Illuminate\Http\Request;

class VisitAgreement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RosterUpdate:VisitAgreement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically adds members of the ZHU/ZJX ARTCC to the roster as visitors as per the visiting agreement.';

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
     * Randomly generate a letter
     *
     * @return string
     */
     public function genRandLetter() {
         $fi_int = rand(1, 26);

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

         return $fn_initial;
     }

     /**
      * Match a letter to a number
      *
      * @return string
      */
      public function letterFromNum($fi_int) {
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

          return $fn_initial;
      }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client_zhu = new Client();
        $res_zhu = $client_zhu->get('https://api.vatusa.net/v2/facility/zhu/roster?apikey='.Config::get('vatusa.api_key'));
        $roster_zhu = json_decode($res_zhu->getBody());

        $client_zjx = new Client();
        $res_zjx = $client_zjx->get('https://api.vatusa.net/v2/facility/zjx/roster?apikey='.Config::get('vautsa.api_key'));
        $roster_zjx = json_decode($res_zjx->getBody());

       
        //Runs for ZJX
        foreach($roster_zjx as $r) {
            // Last result will be false
            if(! ($r === true || $r === false)) {
                if (User::find($r->cid) !== null) {
                    $user = User::find($r->cid);
                    $user->fname = $r->fname;
                    $user->lname = $r->lname;
                    if ($r->email != null) {
                        $user->email = $r->email;
                    } else {
                        $user->email = 'No email';
                    }
                    $user->rating_id = $r->rating;
                    $user->visitor = '1';
                    $visitrej = VisitRej::where('cid', $r->cid)->first();
                    if ($visitrej == null) {
                        if ($user->status == 2) {
                            $user->status = 1;
                        }
                    }
                    $user->visitor_from = 'ZJX';
                    if ($r->facility_join == '1900-01-01T00:00:01+00:00') {
                        $user->added_to_facility = substr($r->created_at, 0, 10) . ' ' . substr($r->created_at, 11, 8);
                    } else {
                        $user->added_to_facility = substr($r->facility_join, 0, 10) . ' ' . substr($r->facility_join, 11, 8);
                    }
                    $user->save();
                } else {
                    $visitrej = VisitRej::where('cid', $r->cid)->first();
                    if ($visitrej == null) {
                        $user = new User;
                        $user->id = $r->cid;
                        $user->fname = $r->fname;
                        $user->lname = $r->lname;
                        if ($r->email != null) {
                            $user->email = $r->email;
                        } else {
                            $user->email = 'No email';
                        }
                        $user->rating_id = $r->rating;
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
                        $user->visitor = '1';
                        $user->visitor_from = 'ZJX';
                        $user->status = '1';
                        if ($r->facility_join == '1900-01-01T00:00:01+00:00') {
                            $user->added_to_facility = substr($r->created_at, 0, 10) . ' ' . substr($r->created_at, 11, 8);
                        } else {
                            $user->added_to_facility = substr($r->facility_join, 0, 10) . ' ' . substr($r->facility_join, 11, 8);
                        }
                        $user->save();

                        //Assigns controller initials
                        $user = User::find($r->cid);

                        $users_inc_v = User::where('status', '!=', 2)->where('visitor_from', '!=', 'ZHU')->where('visitor_from', '!=', 'ZJX')->orWhereNull('visitor_from')->get();
                        $fn_initial = strtoupper(substr($user->fname, 0, 1));
                        $ln_initial = strtoupper(substr($user->lname, 0, 1));
                        $f_initial = $fn_initial;
                        $l_initial = $ln_initial;

                        $trys = 0;
                        b:
                        $trys++;
                        $initials = $fn_initial . $ln_initial;

                        $yes = 1;
                        foreach ($users_inc_v as $u) {
                            if ($u->initials == $initials) {
                                $yes = 0;
                            }
                        }

                        if ($yes == 1) {
                            $user->initials = $initials;
                            $user->save();
                        } else {
                            // Check first initial with all letters
                            if ($trys <= 26) {
                                $fn_initial = $f_initial;
                                $ln_initial = $this->letterFromNum($trys);

                                goto b;
                            } else {
                                $ln_initial = $this->genRandLetter();
                            }

                            if ($trys >= 27 && $trys <= 52) {
                                $ln_initial = $l_initial;
                                $fn_initial = $this->letterFromNum($trys - 26);

                                goto b;
                            } else {
                                $fn_initial = $this->genRandLetter();
                            }

                            goto b;
                        }
                    }
                }
            }
        }

        $users_zjx = $users = User::where('visitor', '1')->where('status', '1')->where('visitor_from', 'ZJX')->get()->pluck('id');
        $users_zhu = $users = User::where('visitor', '1')->where('status', '1')->where('visitor_from', 'ZHU')->get()->pluck('id');

        foreach($users_zjx as $u) {
            $delete = 0;
            foreach($roster_zjx as $r) {
                // Last result will be false
                if(! ($r === true || $r === false)) {
                    $id = $r->cid;
                    if($u == $id) {
                        $delete = 1;
                    }
                }
            }
            if($delete == '0') {
                $use = User::find($u);
                $event_requests = EventRegistration::where('controller_id', $use->id)->get();
                foreach($event_requests as $e) {
                    $e->delete();
                }

                $use->status = 2;
                $use->save();
            }
        }

        foreach($users_zhu as $u) {
            $delete = 0;
            foreach($roster_zhu as $r) {
                // Last result will be false
                if(! ($r === true || $r === false)) {
                    $id = $r->cid;
                    if($u == $id) {
                        $delete = 1;
                    }
                }
            }
            if($delete == '0') {
                $use = User::find($u);
                $event_requests = EventRegistration::where('controller_id', $use->id)->get();
                foreach($event_requests as $e) {
                    $e->delete();
                }

                $use->status = 2;
                $use->save();
            }
        }
    }
}
