<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Audit;
use App\Announcement;
use App\Bronze;
use App\Calendar;
use App\ControllerLog;
use App\Event;
use App\EventPosition;
use App\EventRegistration;
use App\Feedback;
use App\File;
use App\Incident;
use App\Metar;
use App\PositionPreset;
use App\PresetPosition;
use App\Pyrite;
use App\Scenery;
use App\SoloCert;
use App\User;
use App\Visitor;
use App\VisitRej;
use Artisan;
use Auth;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mail;
use Storage;

class AdminDash extends Controller
{
    public function showScenery() {
        $scenery = Scenery::orderBy('airport', 'ASC')->get();

        $fsx = $scenery->where('sim', 0);
        $xp = $scenery->where('sim', 1);
        $afcad = $scenery->where('sim', 2);

        return view('dashboard.admin.scenery.index')->with('fsx', $fsx)->with('xp', $xp)->with('afcad', $afcad);
    }

    public function viewScenery($id) {
        $scenery = Scenery::find($id);

        return view('dashboard.admin.scenery.view')->with('scenery', $scenery);
    }

    public function newScenery() {
        return view('dashboard.admin.scenery.new');
    }

    public function storeScenery(Request $request) {
        $validator = $request->validate([
            'apt' => 'required',
            'url' => 'required|unique:scenery,link',
            'sim' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'dev' => 'required'
        ]);

        $scenery = new Scenery;
        $scenery->airport = Input::get('apt');
        $scenery->developer = Input::get('dev');
        $scenery->sim = Input::get('sim');
        $scenery->link = Input::get('url');
        $scenery->price = Input::get('price');
        $scenery->currency = Input::get('currency');
        $scenery->image1 = Input::get('image1');
        $scenery->image2 = Input::get('image2');
        $scenery->image3 = Input::get('image3');
        $scenery->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' added new scenery.';
        $audit->save();

        return redirect('/dashboard/admin/scenery')->with('success', 'Scenery added successfully.');
    }

    public function editScenery(Request $request, $id) {
        $scenery = Scenery::find($id);

        return view('dashboard.admin.scenery.edit')->with('scenery', $scenery);
    }

    public function saveScenery(Request $request, $id) {
        $validator = $request->validate([
            'apt' => 'required',
            'url' => 'required',
            'sim' => 'required',
            'dev' => 'required'
        ]);

        $scenery = Scenery::find($id);
        $scenery->airport = Input::get('apt');
        $scenery->developer = Input::get('dev');
        $scenery->sim = Input::get('sim');
        $scenery->link = Input::get('url');
        $scenery->image1 = Input::get('image1');
        $scenery->image2 = Input::get('image2');
        $scenery->image3 = Input::get('image3');
        $scenery->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated a scenery.';
        $audit->save();

        return redirect('/dashboard/admin/scenery')->with('success', 'Scenery edited successfully.');
    }

    public function deleteScenery($id) {
        $scenery = Scenery::find($id);
        $scenery->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed a scenery.';
        $audit->save();

        return redirect('/dashboard/admin/scenery')->with('success', 'Scenery deleted successfully.');
    }

    public function showAirports() {
        $airports = Airport::orderBy('ltr_3', 'ASC')->get();

        return view('dashboard.admin.airports.index')->with('airports', $airports);
    }

    public function newAirport() {
        return view('dashboard.admin.airports.new');
    }

    public function storeAirport(Request $request) {
        $validator = $request->validate([
            'name' => 'required',
            'FAA' => 'required|unique:airports,ltr_3',
            'ICAO' => 'required'
        ]);

        $a = new Airport;
        $a->name = Input::get('name');
        $a->ltr_3 = Input::get('FAA');
        $a->ltr_4 = Input::get('ICAO');
        $a->save();

        $metar = new Metar;
        $metar->icao = Input::get('ICAO');
        $metar->save();

        Artisan::call('Weather:UpdateWeather');

        return redirect('/dashboard/admin/airports')->with('success', 'The airport was added successfully.');
    }

    public function addAirportToHome($id) {
        $airport = Airport::find($id);
        $airport->front_pg = 1;
        $airport->save();

        return redirect()->back()->with('success', 'You have successfully added this airport to the home page.');
    }

    public function removeAirportFromHome($id) {
        $airport = Airport::find($id);
        $airport->front_pg = 0;
        $airport->save();

        return redirect()->back()->with('success', 'You have successfully removed this airport from the home page.');
    }

    public function deleteAirport($id) {
        $airport = Airport::find($id);
        $airport->delete();

        return redirect('/dashboard/admin/airports')->with('success', 'The airport has been deleted successfully.');
    }

    public function showRoster() {
        $hcontrollers = User::where('visitor', '0')->orderBy('lname', 'ASC')->get();
        $vcontrollers = User::where('visitor', '1')->orderBy('lname', 'ASC')->get();

        $mtr = $hcontrollers->filter(function($user){
            return $user->hasRole('mtr');
        });
        $ins = $hcontrollers->filter(function($user){
            return $user->hasRole('ins');
        });

        return view('dashboard.admin.roster.index')->with('hcontrollers', $hcontrollers)->with('vcontrollers', $vcontrollers)->with('mtr', $mtr)->with('ins', $ins);
    }

    public function showRosterPurge($year = null, $month = null) {
        if ($year == null)
            $year = date('y');

        if ($month == null)
            $month = date('n');

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $homec = User::where('visitor', 0)->where('status', 1)->orderBy('lname', 'ASC')->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->where('visitor_from', '!=', 'ZHU')->where('visitor_from', '!=', 'ZJX')->orderBy('lname', 'ASC')->get();
        $trainc = User::orderBy('lname', 'ASC')->get()->filter(function($user){
            return $user->hasRole('mtr') || $user->hasRole('ins');
        });

        if($month == 1) {
            $last_year = $year - 1;
        } else {
            $last_year = $year;
        }

        if($month == 1) {
            $last_month = 12;
        } else {
            $last_month = $month - 1;
        }

        $last_stats = ControllerLog::aggregateAllControllersByPosAndMonth($last_year, $last_month);

        return view('dashboard.admin.roster.purge')->with('stats', $stats)->with('last_stats', $last_stats)->with('homec', $homec)->with('visitc', $visitc)
                                                   ->with('trainc', $trainc)->with('month', $month)->with('year', $year);
    }

    public function editController($id) {
        $user = User::find($id);

        return view('dashboard.admin.roster.edit')->with('user', $user);
    }

    public function updateController(Request $request, $id) {
        $user = User::find($id);

        if(Auth::user()->can('roster')) {
            $user->del = Input::get('del');
            $user->gnd = Input::get('gnd');
            if($user->twr == 99) {
                if(Input::get('twr') != 0) {
                    $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                    if($solo) {
                        $solo->status = 1;
                        $solo->save();
                    }
                    $user->twr = Input::get('twr');
                } else {
                    $user->twr = 99;
                }
            } elseif(Input::get('twr') == 99) {
                $expire = Carbon::now()->addMonth()->format('Y-m-d');
                $user->twr = Input::get('twr');
                $cert = new SoloCert;
                $cert->cid = $id;
                $cert->pos = 0;
                $cert->expiration = $expire;
                $cert->status = 0;
                $cert->save();
            } else {
                $user->twr = Input::get('twr');
            }
            if($user->app == 99) {
                if(Input::get('app') != 0) {
                    $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                    if($solo) {
                        $solo->status = 1;
                        $solo->save();
                    }
                    $user->app = Input::get('app');
                } else {
                    $user->app = 99;
                }
            } else {
                $user->app = Input::get('app');
            }
            if($user->ctr == 99) {
                if(Input::get('ctr') != 0) {
                    $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                    if($solo) {
                        $solo->status = 1;
                        $solo->save();
                    }
                    $user->ctr = Input::get('ctr');
                } else {
                    $user->ctr = 99;
                }
            } else {
                $user->ctr = Input::get('ctr');
            }
            $user->initials = Input::get('initials');
            $user->max = Input::get('max');
          
            if(Input::get('visitor') == null) {
                $user->visitor = 0;
            } elseif(Input::get('visitor') == 1) {
                $user->visitor = 1;
            }
            if(Input::get('canTrain') == null) {
                $user->canTrain = 0;
            } elseif(Input::get('canTrain') == 1) {
                $user->canTrain = 1;
            }
            if(Input::get('canEvents') == null) {
                $user->canEvents = 0;
            } elseif(Input::get('canEvents') == 1) {
                $user->canEvents = 1;
            }
            if(Input::get('api_exempt') == null) {
                $user->api_exempt = 0;
            } elseif(Input::get('api_exempt') == 1) {
                $user->api_exempt = 1;
            }
            $user->status = Input::get('status');
            $user->visitor_from = Input::get('visitor_from');
            $user->save();

            if($user->hasRole(['atm', 'datm', 'ta', 'ata', 'wm', 'awm', 'fe', 'afe', 'ec', 'aec']) == true) {
                if($user->hasRole('atm')) {
                    $user->detachRole('atm');
                } elseif($user->hasRole('datm')) {
                    $user->detachRole('datm');
                } elseif($user->hasRole('ta')) {
                    $user->detachRole('ta');
                } elseif($user->hasRole('ata')) {
                    $user->detachRole('ata');
                } elseif($user->hasRole('wm')) {
                    $user->detachRole('wm');
                } elseif($user->hasRole('awm')) {
                    $user->detachRole('awm');
                } elseif($user->hasRole('fe')) {
                    $user->detachRole('fe');
                } elseif($user->hasRole('afe')) {
                    $user->detachRole('afe');
                } elseif($user->hasRole('ec')) {
                    $user->detachRole('ec');
                } elseif($user->hasRole('aec')) {
                    $user->detachRole('aec');
                }
            }

            if(Input::get('staff') == 1) {
                $user->attachRole('atm');
            } elseif(Input::get('staff') == 2) {
                $user->attachRole('datm');
            } elseif(Input::get('staff') == 3) {
                $user->attachRole('ta');
            } elseif(Input::get('staff') == 4) {
                $user->attachRole('ata');
            } elseif(Input::get('staff') == 5) {
                $user->attachRole('wm');
            } elseif(Input::get('staff') == 6) {
                $user->attachRole('awm');
            } elseif(Input::get('staff') == 7) {
                $user->attachRole('fe');
            } elseif(Input::get('staff') == 8) {
                $user->attachRole('afe');
            } elseif(Input::get('staff') == 9) {
                $user->attachRole('ec');
            } elseif(Input::get('staff') == 10) {
                $user->attachRole('aec');
            }

            if($user->hasRole(['mtr', 'ins']) == true) {
                if($user->hasRole('mtr')) {
                    $user->detachRole('mtr');
                    $user->save();
                } elseif($user->hasRole('ins')) {
                    $user->detachRole('ins');
                    $user->save();
                }
            }
            if(Input::get('training') == 1) {
                $user->attachRole('mtr');
                if($user->train_pwr == null) {
                    $user->train_pwr = 1;
                    $user->monitor_pwr = 1;
                    $user->save();
                }
            } elseif(Input::get('training') == 2) {
                $user->attachRole('ins');
                if($user->train_pwr == null) {
                    $user->train_pwr = 6;
                    $user->monitor_pwr = 6;
                    $user->save();
                }
            }
        } else {
            $user->del = Input::get('del');
            $user->gnd = Input::get('gnd');
            if($user->twr == 99) {
                if(Input::get('twr') != 0) {
                    $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                    if($solo) {
                        $solo->status = 1;
                        $solo->save();
                    }
                    $user->twr = Input::get('twr');
                } else {
                    $user->twr = 99;
                }
            } elseif(Input::get('twr') == 99) {
                $expire = Carbon::now()->addMonth()->format('Y-m-d');
                $user->twr = Input::get('twr');
                $cert = new SoloCert;
                $cert->cid = $id;
                $cert->pos = 0;
                $cert->expiration = $expire;
                $cert->status = 0;
                $cert->save();
            } else {
                $user->twr = Input::get('twr');
            }
            if($user->app == 99) {
                if(Input::get('app') != 0) {
                    $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                    if($solo) {
                        $solo->status = 1;
                        $solo->save();
                    }
                    $user->app = Input::get('app');
                } else {
                    $user->app = 99;
                }
            } else {
                $user->app = Input::get('app');
            }
            if($user->ctr == 99) {
                if(Input::get('ctr') != 0) {
                    $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                    if($solo) {
                        $solo->status = 1;
                        $solo->save();
                    }
                    $user->ctr = Input::get('ctr');
                } else {
                    $user->ctr = 99;
                }
            } else {
                $user->ctr = Input::get('ctr');
            }
            $user->save();
        }

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' made changes to '.$user->full_name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/roster')->with('success', 'Controller updated successfully.');
    }

    public function disallowVisitReq($id) {
        $user = User::find($id);
        $name = $user->full_name;
        $visitrej = new VisitRej;
        $visitrej->cid = $id;
        $visitrej->staff_cid = Auth::id();
        $visitrej->save();
        $user->status = 2;
        $user->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed '.$name.' from the visitor agreement.';
        $audit->save();

        return redirect('/dashboard/controllers/roster')->with('success', 'Controller removed from the visitor agreement.');
    }

    public function allowVisitReq(Request $request) {
        $id = $request->cid;
        $visitrej = VisitRej::where('cid', $id)->first();
        $visitrej->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' readded '.$name.' to the visitor agreement.';
        $audit->save();

        return redirect('/dashboard/controllers/roster')->with('success', 'Controller allowed to visit.');
    }

    public function showVisitRequests() {
        $new = Visitor::where('status', 0)->orderBy('created_at', 'ASC')->get();
        $accepted = Visitor::where('status', 1)->orderBy('updated_at', 'ASC')->get();
        $rejected = Visitor::where('status', 2)->orderBy('updated_at', 'ASC')->get();

        return view('dashboard.admin.roster.vis_index')->with('new', $new)->with('accepted', $accepted)->with('rejected', $rejected);
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

    public function acceptVisitRequest($id) {
        $visitor = Visitor::find($id);
        $visitor->updated_by = Auth::id();
        $visitor->status = 1;
        $visitor->save();

        Mail::send('emails.visit.accept', ['visitor' => $visitor], function($message) use ($visitor){
            $message->from('visitors@notams.ztlartcc.org', 'vZTL ARTCC Visiting Department')->subject('Visitor Request Accepted');
            $message->to($visitor->email)->cc('datm@ztlartcc.org');
        });

        $parts = explode(" ",$visitor->name);
        $fname = $parts[0];
        $lname = $parts[1];

        //Assigns controller initials
        $users_inc_v = User::where('visitor_from', '!=', 'ZHU')->where('visitor_from', '!=', 'ZJX')->orWhereNull('visitor_from')->get();
        $fn_initial = strtoupper(substr($fname, 0, 1));
        $ln_initial = strtoupper(substr($lname, 0, 1));
        $f_initial = $fn_initial;
        $l_initial = $ln_initial;

        $trys = 0;
        a:
        $trys++;
        $initials = $fn_initial.$ln_initial;

        $yes = 1;
        foreach($users_inc_v as $u) {
            if($u->initials == $initials) {
                $yes = 0;
            }
        }

        if($yes == 1) {
            $initials = $initials;
        } else {
            // Check first initial with all letters
            if($trys <= 26) {
                $fn_initial = $f_initial;
                $ln_initial = $this->letterFromNum($trys);

                goto a;
            } else {
                $ln_initial = $this->genRandLetter();
            }

            if($trys >= 27 && $trys <= 52) {
                $ln_initial = $l_initial;
                $fn_initial = $this->letterFromNum($trys - 26);

                goto a;
            } else {
                $fn_initial = $this->genRandLetter();
            }

            goto a;
        }

        return view('dashboard.admin.roster.new_vis')->with('visitor', $visitor)->with('initials', $initials)->with('fname', $fname)->with('lname', $lname);
    }

    public function manualAddVisitor(Request $request) {
        $validator = $request->validate([
            'cid' => 'required'
        ]);

        $client = new Client(['exceptions' => false]);
        $response = $client->request('GET', 'https://api.vatusa.net/v2/user/'.$request->cid.'?apikey='.Config::get('vatusa.api_key'));
        $result = $response->getStatusCode();
        if($result == '200') {
            $visitor = json_decode($response->getBody());
            $visitor = $visitor->data;

            //Assigns controller initials
            $users_inc_v = User::where('visitor_from', '!=', 'ZHU')->where('visitor_from', '!=', 'ZJX')->orWhereNull('visitor_from')->get();
            $fn_initial = strtoupper(substr($visitor->fname, 0, 1));
            $ln_initial = strtoupper(substr($visitor->lname, 0, 1));
            $f_initial = $fn_initial;
            $l_initial = $ln_initial;

            $trys = 0;
            a:
            $trys++;
            $initials = $fn_initial.$ln_initial;

            $yes = 1;
            foreach($users_inc_v as $u) {
                if($u->initials == $initials) {
                    $yes = 0;
                }
            }

            if($yes == 1) {
                $initials = $initials;
            } else {
                // Check first initial with all letters
                if($trys <= 26) {
                    $fn_initial = $f_initial;
                    $ln_initial = $this->letterFromNum($trys);

                    goto a;
                } else {
                    $ln_initial = $this->genRandLetter();
                }

                if($trys >= 27 && $trys <= 52) {
                    $ln_initial = $l_initial;
                    $fn_initial = $this->letterFromNum($trys - 26);

                    goto a;
                } else {
                    $fn_initial = $this->genRandLetter();
                }

                goto a;
            }
        } else {
            $visitor = null;
            $initials = null;
        }

        return view('dashboard.admin.roster.manual_vis')->with('visitor', $visitor)->with('initials', $initials);
    }

    public function rejectVisitRequest(Request $request, $id) {
        $validator = $request->validate([
            'reject_reason' => 'required'
        ]);
        $visitor = Visitor::find($id);
        $visitor->updated_by = Auth::id();
        $visitor->status = 2;
        $visitor->reject_reason = $request->reject_reason;
        $visitor->save();

        Mail::send(['html' => 'emails.visit.reject'], ['visitor' => $visitor], function($message) use ($visitor) {
            $message->from('visitors@notams.ztlartcc.org', 'vZTL ARTCC Visiting Department')->subject('Visitor Request Rejected');
            $message->to($visitor->email)->cc('datm@ztlartcc.org');
        });

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' rejected the visit request for '.$visitor->name.'.';
        $audit->save();

        return redirect('/dashboard/admin/roster/visit/requests')->with('success', 'The visit request has been rejected successfully.');
    }

    public function storeVisitor(Request $request) {
        $user = new User;
        $user->id = Input::get('cid');
        $user->fname = Input::get('fname');
        $user->lname = Input::get('lname');
        $user->email = Input::get('email');
        $user->initials = Input::get('initials');
        $user->rating_id = Input::get('rating_id');
        if(Input::get('rating_id') == 2) {
            $user->del = 1;
            $user->gnd = 1;
        } elseif(Input::get('rating_id') == 3) {
            $user->del = 1;
            $user->gnd = 1;
            $user->twr = 1;
        } elseif(Input::get('rating_id') == 4 || Input::get('rating_id') == 5 || Input::get('rating_id') == 7 || Input::get('rating_id') == 8 || Input::get('rating_id') == 10) {
            $user->del = 1;
            $user->gnd = 1;
            $user->twr = 1;
            $user->app = 1;
        }
        $user->visitor = '1';
        $user->visitor_from = Input::get('visitor_from');
        $user->status = '1';
        $user->added_to_facility = Carbon::now();
        $user->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' added the visitor '.$user->full_name.'.';
        $audit->save();

        return redirect('/dashboard/admin/roster/visit/requests')->with('success', 'The visitor has been successfully added to the roster.');
    }

    public function removeVisitor($id) {
        $user = User::find($id);
        $name = $user->full_name;
        if($user->visitor == 0) {
            return redirect()->back()->with('error', 'You can only remove visitors this way. If you are trying to remove a home controller, please do this from the VATUSA website.');
        } else {
            $event_requests = EventRegistration::where('controller_id', $user->id)->get();
            foreach($event_requests as $e) {
                $e->delete();
            }
            $user->status = 2;
            $user->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' removed the visitor '.$name.'.';
            $audit->save();
            if(filter_var($user->email, FILTER_VALIDATE_EMAIL)) { // Added this to deal with case when user does not have an email address on file
             Mail::send('emails.remove_visitor', ['user' => $user], function($message) use ($user){
                        $message->from('datm@ztlartcc.org', 'vZTL ARTCC Staff')->subject('Notification of ZTL Roster Removal');
                        $message->to($user->email)->cc('datm@ztlartcc.org');
             });
			}
            return redirect('/dashboard/controllers/roster')->with('success', 'The visitor has been removed successfully.');
        }
    }

    public function viewCalendar() {
        $calendar = Calendar::where('type', '1')->get()->sortByDesc(function($news) {
            return strtotime($news->date.' '.$news->time);
        });
        $news = Calendar::where('type', '2')->get()->sortByDesc(function($news) {
            return strtotime($news->date.' '.$news->time);
        });

        return view('dashboard.admin.calendar.index')->with('calendar', $calendar)->with('news', $news);
    }

    public function viewCalendarEvent($id) {
        $calendar = Calendar::find($id);

        return view('dashboard.admin.calendar.view')->with('calendar', $calendar);
    }

    public function newCalendarEvent() {
        return view('dashboard.admin.calendar.new');
    }

    public function storeCalendarEvent(Request $request) {
        $validator = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'body' => 'required',
            'type' => 'required'
        ]);

        $calendar = new Calendar;
        $calendar->title = Input::get('title');
        $calendar->date = Input::get('date');
        $calendar->time = Input::get('time');
        $calendar->body = Input::get('body');
        $calendar->type = Input::get('type');
        $calendar->created_by = Auth::id();
        $calendar->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' added a new calendar or news event.';
        $audit->save();

        return redirect('/dashboard/admin/calendar')->with('success', 'The calendar event or news posting has been created.');

    }

    public function editCalendarEvent($id) {
        $calendar = Calendar::find($id);
        return view('dashboard.admin.calendar.edit')->with('calendar', $calendar);
    }

    public function saveCalendarEvent(Request $request, $id) {
        $calendar = Calendar::find($id);

        $validator = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'body' => 'required',
            'type' => 'required'
        ]);

        $calendar->title = Input::get('title');
        $calendar->date = Input::get('date');
        $calendar->time = Input::get('time');
        $calendar->body = Input::get('body');
        $calendar->type = Input::get('type');
        $calendar->updated_by = Auth::id();
        $calendar->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' edited the calendar event '.$calendar->title.'.';
        $audit->save();

        return redirect('/dashboard/admin/calendar')->with('success', 'The calendar event or news posting has been edited.');
    }

    public function deleteCalendarEvent($id){
        $calendar = Calendar::find($id);
        $title = $calendar->title;
        $calendar->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed the calendar event '.$title.'.';
        $audit->save();

        return redirect('/dashboard/admin/calendar')->with('success', 'The calendar event or news posting has been deleted.');
    }

    public function toggleCalenderEventVisibilty($id){
          $calendar = Calendar::find($id);
          $type = '';

          if($calendar->visible == 1){
              $calendar->visible = 0;
              $type = 'invisible';
          }elseif($calendar->visible == 0) {
              $calendar->visible = 1;
              $type = 'visible';
          }

          $calendar->save();

          $audit = new Audit;
          $audit->cid = Auth::id();
          $audit->ip = $_SERVER['REMOTE_ADDR'];
          $audit->what = Auth::user()->full_name . ' made ' . $calendar->title . ' ' . $type . '.';
          $audit->save();

            return redirect('/dashboard/admin/calendar')->with('success', 'Changed ' . $calendar->title . ' to be ' . $type . '!');
    }

    public function uploadFile() {
        return view('dashboard.admin.files.upload');
    }

    public function storeFile(Request $request) {
        $validator = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'file' => 'required'
        ]);

        $time = Carbon::now()->timestamp;

        $ext = $request->file('file')->getClientOriginalExtension();
        $name = $request->title.'_'.$time.'.'.$ext;

        $path = $request->file('file')->storeAs(
            '/public/files', $name
        );

        $public_url = '/storage/files/'.$name;

        $file = new File;
        $file->name = Input::get('title');
        $file->type = Input::get('type');
        $file->desc = Input::get('desc');
        $file->path = $public_url;
        $file->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' created the file '.$file->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/files')->with('success', 'The file has been successfully added.');
    }

    public function editFile($id) {
        $file = File::find($id);

        return view('dashboard.admin.files.edit')->with('file', $file);
    }

    public function saveFile(Request $request, $id) {
        $file = File::find($id);
        $file->name = Input::get('title');
        $file->type = Input::get('type');
        $file->desc = Input::get('desc');
        $file->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated the file '.$file->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/files')->with('success', 'The file has been edited successfully.');
    }
	
	public function updateFileDispOrder(Request $request) {
		if(($request->act == 'up')&&($request->pos > 0)) { // If action is move up, swap spots with item that = -1
			$file = File::where('type', $request->typ)->where('disp_order', $request->pos - 1)->first();
			$file->disp_order = $request->pos;
			$file->timestamps = false;
			$file->save();
			$file = File::find($request->id);
			$file->disp_order = $request->pos - 1;
			$file->timestamps = false;
			$file->save();
		}
		elseif($request->act == 'down') { // If action is move down, then +1 to all elements >= order and update
			$file = File::where('type', $request->typ)->where('disp_order', $request->pos + 1)->first();
			if(!is_null($file)) {
				$file->disp_order = $request->pos;
				$file->timestamps = false;
				$file->save();
				$file = File::find($request->id);
				$file->disp_order =  $request->pos + 1;
				$file->timestamps = false;
				$file->save();
			}
		}
		// Rebuild the display and return it to the AJAX caller
		$dispString = "";
		$files = File::where('type', $request->typ)->orderBy('disp_order', 'ASC')->get();
		foreach($files as $f) {
			$dispString .= "<tr>
                                <td>$f->name</td>
                                <td>$f->desc</td>
                                <td>$f->updated_at</td>
                                <td>
								<div class=\"btn-group\">
                                  <a href=\"$f->path\" target=\"_blank\" class=\"btn btn-success simple-tooltip\" data-toggle=\"tooltip\" title=\"Download\"><i class=\"fas fa-download\"></i></a>
                                        <a href=\"/dashboard/admin/files/edit/$f->id\" class=\"btn btn-warning simple-tooltip\" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fas fa-pencil-alt\"></i></a>
                                        <a href=\"/dashboard/admin/files/delete/$f->id\" onclick=\"return confirm(\'Are you sure you want to delete " . $f->name . "?\')\" class=\"btn btn-danger simple-tooltip\" data-toggle=\"tooltip\" title=\"Delete\"><i class=\"fas fa-times\"></i></a>";
			if($f->disp_order > 0) { // Don't show the up button for the first item listed
				$dispString .= "<a onclick=\"itemReorder($f->id,$f->disp_order,$f->type,\'up\');\" class=\"btn btn-info simple-tooltip\" data-toggle=\"tooltip\" title=\"Up\"><i class=\"fas fa-arrow-up\"></i></a>";
			}
			if(count($files) > $f->disp_order + 1) { // Don't show the down button for the last item listed
				$dispString .= "<a onclick=\"itemReorder($f->id,$f->disp_order,$f->type,\'down\');\" class=\"btn btn-info simple-tooltip\" data-toggle=\"tooltip\" title=\"Down\"><i class=\"fas fa-arrow-down\"></i></a>";
			}
			$dispString .= "	</div>
								</td>
                            </tr>";
		}
		echo $dispString;
	}

    public function deleteFile($id) {
        $file = File::find($id);
        $file_path = $file->path;
        $file->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' deleted the file '.$file->name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The file has been deleted successfully.');
    }

    public function showFeedback() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        $feedback = Feedback::where('status', 0)->orderBy('created_at', 'ASC')->get();
        $feedback_p = Feedback::where('status', 1)->orwhere('status', 2)->orderBy('updated_at', 'DSC')->paginate(25);
        return view('dashboard.admin.feedback')->with('feedback', $feedback)->with('feedback_p', $feedback_p)->with('controllers', $controllers);
    }

    public function saveFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->controller_id = $request->controller_id;
        $feedback->position = $request->position;
        $feedback->staff_comments = $request->staff_comments;
        $feedback->comments = $request->pilot_comments;
        $feedback->status = 1;
        $feedback->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' saved feedback '.$feedback->id.' for '.$feedback->controller_name.'.';
        $audit->save();

        $controller = User::find($feedback->controller_id);

        Mail::send(['html' => 'emails.new_feedback'], ['feedback' => $feedback, 'controller' => $controller], function($m) use ($feedback, $controller) {
            $m->from('feedback@notams.ztlartcc.org', 'vZTL ARTCC Feedback Department');
            $m->subject('You Have New Feedback!');
            $m->to($controller->email);
        });

        return redirect()->back()->with('success', 'The feedback has been saved.');
    }

    public function hideFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->controller_id = $request->controller_id;
        $feedback->position = $request->position;
        $feedback->staff_comments = $request->staff_comments;
        $feedback->comments = $request->pilot_comments;
        $feedback->status = 2;
        $feedback->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' archived feedback '.$feedback->id.' for '.$feedback->controller_name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The feedback has been hidden.');
    }

    public function updateFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->controller_id = $request->controller_id;
        $feedback->position = $request->position;
        $feedback->staff_comments = $request->staff_comments;
        $feedback->comments = $request->pilot_comments;
        $feedback->status = $request->status;
        $feedback->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated feedback '.$feedback->id.' for '.$feedback->controller_name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The feedback has been updated.');
    }

    public function emailFeedback(Request $request, $id) {
        $validator = $request->validate([
            'email' => 'required',
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required'
        ]);

        $feedback = Feedback::find($id);
        $replyTo = $request->email;
        $replyToName = $request->name;
        $subject = $request->subject;
        $body = $request->body;
        $sender = Auth::user();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' emailed the pilot for feedback '.$feedback->id.'.';
        $audit->save();

        Mail::send('emails.feedback_email', ['feedback' => $feedback, 'body' => $body, 'sender' => $sender], function($m) use ($feedback, $subject, $replyTo, $replyToName) {
            $m->from('feedback@notams.ztlartcc.org', 'vZTL ARTCC Feedback Department')->replyTo($replyTo, $replyToName);
            $m->subject($subject);
            $m->to($feedback->pilot_email);
        });

        return redirect()->back()->with('success', 'The email has been sent to the pilot successfully.');
    }

    public function sendNewEmail() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        return view('dashboard.admin.email.send')->with('controllers', $controllers);
    }

    public function sendEmail(Request $request) {
        $validator = $request->validate([
            'name' => 'required',
            'reply_to' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $sender = Auth::user();
        $name = $request->name;
        $reply_to = $request->reply_to;
        $bulk = $request->bulk;
        $subject = $request->subject;
        $body = $request->message;

        $controllers = User::where('status', 1)->where('opt', 1)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $hcontrollers = User::where('status', 1)->where('opt', 1)->where('visitor', 0)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $vcontrollers = User::where('status', 1)->where('opt', 1)->where('visitor', 1)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $mentors = User::where('opt', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'mtr');
        })->where('email', '!=', $sender->email)->get()->pluck('email');
        $ins = User::where('opt', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'ins');
        })->where('email', '!=', $sender->email)->get()->pluck('email');
        $train_staff = User::where('opt', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'mtr')->orWhere('name', 'ins');
        })->where('email', '!=', $sender->email)->get()->pluck('email');
        $obs = User::where('status', 1)->where('opt', 1)->where('visitor', 0)->where('rating_id', 1)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $s1 = User::where('status', 1)->where('opt', 1)->where('visitor', 0)->where('rating_id', 2)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $s2 = User::where('status', 1)->where('opt', 1)->where('visitor', 0)->where('rating_id', 3)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $s3 = User::where('status', 1)->where('opt', 1)->where('visitor', 0)->where('rating_id', 4)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');
        $c1 = User::where('rating_id', 5)->orWhere('rating_id', 7)->orWhere('rating_id', 8)->orWhere('rating_id', 10)->where('status', 1)->where('opt', 1)->where('visitor', 0)->where('email', '!=', $sender->email)->orderBy('lname', 'ASC')->get()->pluck('email');

        if($bulk == null) {
            $to = User::find($request->to)->email;
            $emails = [$to];
        } elseif($bulk == 0) {
            $emails = $controllers;
        } elseif($bulk == 1) {
            $emails = $hcontrollers;
        } elseif($bulk == 2) {
            $emails = $vcontrollers;
        } elseif($bulk == 3) {
            $emails = $mentors;
        } elseif($bulk == 4) {
            $emails = $ins;
        } elseif($bulk == 5) {
            $emails = $train_staff;
        } elseif($bulk == 6) {
            $emails = $obs;
        } elseif($bulk == 7) {
            $emails = $s1;
        } elseif($bulk == 8) {
            $emails = $s2;
        } elseif($bulk == 9) {
            $emails = $s3;
        } elseif($bulk == 10) {
            $emails = $c1;
        } else {
            return redirect()->back()->with('error', 'Please select either a controller or a group to send an email to.');
        }

        //Sends to all recipients
        foreach($emails as $e){
            if($e != 'No email') {
                try {
                    Mail::send(['html' => 'emails.send'], ['sender' => $sender, 'body' => $body], function ($m) use ($name, $subject, $e, $reply_to) {
                        $m->from('info@notams.ztlartcc.org', $name)->replyTo($reply_to, $name);
                        $m->subject('[vZTL ARTCC] '.$subject);
                        $m->to($e);
                    });
                } catch(\Exception $except) {
                    // If they have a bad email, change it to no email
                    $bad = User::where('email', $e)->first();
                    $bad->email = 'No email';
                    $bad->save();
                }
            }
        }
        //Copies to the sender
        Mail::send(['html' => 'emails.send'], ['sender' => $sender, 'body' => $body], function ($m) use ($name, $subject, $sender, $reply_to) {
            $m->from('info@notams.ztlartcc.org', $name)->replyTo($reply_to, $name);
            $m->subject('[vZTL ARTCC] '.$subject);
            $m->to($sender->email);
        });

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' sent an email from the send email page.';
        $audit->save();

        return redirect('/dashboard/admin/email/send')->with('success', 'The email has been sent successfully and a copy has been sent to you as well.');
    }

    public function setAnnouncement() {
        $announcement = Announcement::find(1);
        return view('dashboard.admin.announcement')->with('announcement', $announcement);
    }

    public function saveAnnouncement(Request $request) {
        $announcement = Announcement::find(1);
        $announcement->body = $request->body;
        $announcement->staff_member = Auth::id();
        $announcement->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated the announcement.';
        $audit->save();

        return redirect('/dashboard/admin/announcement')->with('success', 'The announcement has been updated successfully.');
    }

    public function showBronzeMic($year = null, $month = null) {
        if ($year == null)
            $year = date('y');

        if ($month == null)
            $month = date('n');

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $year_stats = ControllerLog::aggregateAllControllersByPosAndYear($year, $month);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->get();
        $winner = Bronze::where('month', $month)->where('year', $year)->first();

        $home = $homec->sortByDesc(function($user) use($stats) {
            return $stats[$user->id]->bronze_hrs;
        });
        return view('dashboard.admin.bronze-mic')->with('all_stats', $all_stats)->with('year', $year)
                                                  ->with('month', $month)->with('stats', $stats)->with('year_stats', $year_stats)
                                                  ->with('home', $home)->with('winner', $winner);
    }

    public function setBronzeWinner(Request $request, $year, $month, $hours, $id) {
        $bronze = new Bronze;
        $bronze->controller_id = $id;
        $bronze->month = $month;
        $bronze->year = $year;
        $bronze->month_hours = $hours;
        $bronze->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' set the bronze mic winner for '.$month.'/'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/bronze-mic/'.$year.'/'.$month)->with('success', 'The controller has been set as the bronze mic winner successfully.');
    }

    public function removeBronzeWinner($id, $year, $month) {
        $bronze = Bronze::find($id);
        $bronze->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed the bronze mic winner for '.$month.'/'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/bronze-mic/'.$year.'/'.$month)->with('success', 'The winner has been removed successfully.');
    }

    public function showPyriteMic($year = null) {
        if ($year == null)
            $year = date('y');

        $year_stats = ControllerLog::aggregateAllControllersByPosAndYear($year);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->get();
        $winner = Pyrite::where('year', $year)->first();

        $home = $homec->sortByDesc(function($user) use($year_stats) {
            return $year_stats[$user->id]->bronze_hrs;
        });
        return view('dashboard.admin.pyrite-mic')->with('all_stats', $all_stats)->with('year', $year)
                                                  ->with('year_stats', $year_stats)
                                                  ->with('home', $home)->with('winner', $winner);
    }

    public function setPyriteWinner(Request $request, $year, $hours, $id) {
        $bronze = new Pyrite;
        $bronze->controller_id = $id;
        $bronze->year = $year;
        $bronze->year_hours = $hours;
        $bronze->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' set the pyrite mic winner for 20'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/pyrite-mic/'.$year)->with('success', 'The controller has been set as the pyrite mic winner successfully.');
    }

    public function removePyriteWinner($id, $year) {
        $bronze = Pyrite::find($id);
        $bronze->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed the pyrite mic winner for 20'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/pyrite-mic/'.$year)->with('success', 'The winner has been removed successfully.');
    }

    public function newEvent() {
        return view('dashboard.admin.events.new');
    }

    public function saveNewEvent(Request $request) {
        $validator = $request->validate([
            'name' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required'
        ]);

        if($request->file('banner') != null) {
            $ext = $request->file('banner')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('banner')->storeAs(
                'public/event_banners', $time.'.'.$ext
            );
            $public_url = '/storage/event_banners/'.$time.'.'.$ext;
        } else {
            $public_url = null;
        }

        $event = new Event;
        $event->name = $request->name;
        $event->host = $request->host;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->banner_path = $public_url;
        $event->status = 0;
        $event->reg = 0;
        $event->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' created the event '.$event->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/events/view/'.$event->id)->with('success', 'The event has been created successfully.');
    }

    public function editEvent($id) {
        $event = Event::find($id);
        return view('dashboard.admin.events.edit')->with('event', $event);
    }

    public function saveEvent(Request $request, $id) {
        $validator = $request->validate([
            'name' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required'
        ]);

        $event = Event::find($id);

        if($request->file('banner') != null) {
            $ext = $request->file('banner')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('banner')->storeAs(
                'public/event_banners', $time.'.'.$ext
            );
            $public_url = '/storage/event_banners/'.$time.'.'.$ext;
        } else {
            $public_url = $event->banner_path;
        }

        $event->name = $request->name;
        $event->host = $request->host;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->banner_path = $public_url;
        $event->status = 0;
        $event->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' edited the event '.$event->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/events/view/'.$event->id)->with('success', 'The event has been edited successfully.');
    }

    public function deleteEvent($id) {
        $event = Event::find($id);
        $name = $event->name;
        $positions = EventPosition::where('event_id', $event->id)->get();
        $reg = EventRegistration::where('event_id', $event->id)->get();

        foreach($reg as $r) {
            $r->delete();
        }
        foreach($positions as $r) {
            $r->delete();
        }

        $event->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' deleted the event '.$name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/events')->with('success', 'The event has been deleted successfully.');
    }

    public function addPosition(Request $request, $id) {
        $event = Event::find($id);

        $position = new EventPosition;
        $position->event_id = $id;
        $position->name = $request->name;
        $position->save();

        return redirect()->back()->with('success', 'The position was added successfully.');
    }

    public function removePosition($id) {
        $position = EventPosition::find($id);
        $position->delete();

        $requests = EventRegistration::where('position_id', $id)->get();
        foreach($requests as $r) {
            $r->delete();
        }

        return redirect()->back()->with('success', 'The position has been removed successfully.');
    }

    public function toggleRegistration($id) {
        $event = Event::find($id);

        if($event->reg == 0) {
            $event->reg = 1;
            $event->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' opened registration for the event '.$event->name.'.';
            $audit->save();
        } elseif($event->reg == 1) {
            $event->reg = 0;
            $event->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' closed registration for the event '.$event->name.'.';
            $audit->save();
        }

        return redirect('/dashboard/controllers/events/view/'.$id)->with('success', 'The registration has been toggle successfully.');
    }

    public function assignPosition(Request $request, $id) {
        $reg = EventRegistration::find($id);
        $reg->position_id = $request->position;
        $reg->start_time = $request->start_time;
        $reg->end_time = $request->end_time;
        $reg->status = 1;
        $reg->save();

        return redirect()->back()->with('success', 'The position has been assigned successfully.');
    }

    public function unassignPosition($id) {
        $position = EventRegistration::find($id);
        $position->status = 0;
        $position->save();

        return redirect()->back()->with('success', 'The position assignment has been removed successfully.');
    }

    public function manualAssign(Request $request, $id) {
        $validator = $request->validate([
            'controller' => 'required',
            'position' => 'required'
        ]);

        $reg = new EventRegistration;
        $reg->event_id = $id;
        $reg->controller_id = $request->controller;
        $reg->position_id = $request->position;
        $reg->start_time = $request->start_time;
        $reg->end_time = $request->end_time;
        $reg->status = 1;
        $reg->reminder = 1;
        $reg->choice_number = 0;
        $reg->save();

        return redirect()->back()->with('success', 'The position has been assigned successfully.');
    }

    public function setEventActive($id) {
        $event = Event::find($id);
        $event->status = 1;
        $event->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' set the event '.$event->name.' as active.';
        $audit->save();

        return redirect()->back()->with('success', 'The event has been unhidden successfully.');
    }

    public function hideEvent($id) {
        $event = Event::find($id);
        $event->status = 0;
        $event->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' hid the event '.$event->name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The event has been hidden successfully.');
    }

    public function setEventPositionPreset(Request $request, $id) {
        $positions = EventPosition::where('event_id', $id)->orderBy('id', 'DSC')->get();
        $last_preset_position = PresetPosition::orderBy('id', 'DSC')->first()->id;
        $last = $last_preset_position + 1;
        $preset_positions = $positions->count() + $last_preset_position;

        $position_preset = new PositionPreset;
        $position_preset->name = $request->name;
        $position_preset->first_position = $last;
        $position_preset->last_position = $preset_positions;
        $position_preset->save();

        foreach($positions as $p) {
            $preset = new PresetPosition;
            $preset->name = $p->name;
            $preset->save();
        }

        return redirect()->back()->with('success', 'The position preset has been added successfully');
    }

    public function retrievePositionPreset(Request $request, $id) {
        $preset = PositionPreset::find($request->p_id);
        $first = $preset->first_position;
        $last = $preset->last_position;

        $ids = range($first, $last);

        $presets = PresetPosition::find($ids);

        foreach($presets as $p) {
            $position = new EventPosition;
            $position->event_id = $id;
            $position->name = $p->name;
            $position->save();
        }

        return redirect('/dashboard/controllers/events/view/'.$id)->with('success', 'The position preset has been loaded successfully.');
    }

    public function deletePositionPreset(Request $request) {
        $preset = PositionPreset::find($request->p_id);
        $preset->delete();

        return redirect()->back()->with('success', 'The position preset has been removed successfully.');
    }

    public function incidentReportIndex() {
        $new_reports = Incident::where('status', 0)->orderBy('created_at', 'DSC')->get();
        $archive_reports = Incident::where('status', 1)->orderBy('created_at', 'DSC')->paginate(20);

        return view('dashboard.admin.incident_reports.index')->with('new_reports', $new_reports)->with('archive_reports', $archive_reports);
    }

    public function viewIncidentReport($id) {
        $incident = Incident::find($id);

        return view('dashboard.admin.incident_reports.view')->with('incident', $incident);
    }

    public function archiveIncident($id) {
        $incident = Incident::find($id);
        $incident->controller_id = null;
        $incident->reporter_id = null;
        $incident->status = 1;
        $incident->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' archived incident report '.$id.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The incident has been reported successfully.');
    }

    public function deleteIncident($id) {
        $incident = Incident::find($id);
        $incident->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' deleted incident report '.$id.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The incident has been deleted successfully.');
    }

    public function showAudits() {
        $audits = Audit::orderBy('created_at', 'DSC')->paginate(50);
        return view('dashboard.admin.audits')->with('audits', $audits);
    }
}
