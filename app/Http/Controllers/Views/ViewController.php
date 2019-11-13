<?php

namespace App\Http\Controllers\Views;
use App\Downloads\File;
use App\VatsimData\ATC;
use App\VatsimData\Metar;
use App\WebData\Airport;
use App\Logs\ControllerLogUpdate;
use App\Logs\ControllerLog;
use App\WebData\Calendar;
use App\Events\Event;
use App\VatsimData\Overflight;
use App\VatsimData\OverflightUpdate;
use App\Mship\User;
use App\WebData\Scenery;
use Carbon\Carbon;
use Config;
use Mail;

class ViewController extends \App\Http\Controllers\Controller
{
    public function home() {
        $atc = ATC::get();
        if($atc) {
            $atl_ctr = 0;
            $atl_app = 0;
            $atl_twr = 0;
            $clt_twr = 0;
            foreach($atc as $a) {
                $field = substr($a->position, 0, 3);
                $position = substr($a->position, -3);
                if($field == 'ATL') {
                    if($position == 'TWR' || $position == 'GND') {
                        $atl_twr = 1;
                    } elseif($position == 'APP' || $position == 'DEP') {
                        $atl_app = 1;
                    } elseif($position == 'CTR') {
                        $atl_ctr = 1;
                    }
                } elseif($field == 'CLT') {
                    if($position == 'TWR' || $position == 'GND' || $position == 'APP' || $position == 'DEP') {
                        $clt_twr = 1;
                    }
                }
            }
        }


        $airports = Airport::where('front_pg', 1)->orderBy('ltr_4', 'ASC')->get();
        $metar_update = Metar::first();
        if($metar_update != null) {
            $metar_last_updated = substr($metar_update, -10, 5);
        } else {
            $metar_last_updated = null;
        }

        $controllers = ATC::get();
        $last_update = ControllerLogUpdate::first();
        $controllers_update = substr($last_update->created_at, -8, 5);

        $now = Carbon::now();

        $calendar = Calendar::where('type', '1')->get()->filter(function($news) use ($now) {
            return strtotime($news->date.' '.$news->time) > strtotime($now);
        })->sortBy(function($news) {
            return strtotime($news->date.' '.$news->time);
        });

        $news = Calendar::where('type', '2')->where('visible', '1')->get()->filter(function($news) use ($now) {
            return strtotime($news->date.' '.$news->time) < strtotime($now);
        })->sortByDesc(function($news) {
            return strtotime($news->date.' '.$news->time);
        });

        $events = Event::where('status', 1)->get()->filter(function($e) use ($now) {
            return strtotime($e->date.' '.$e->start_time) > strtotime($now);
        })->sortBy(function($e) {
            return strtotime($e->date);
        });

        $flights = Overflight::where('dep', '!=', '')->where('arr', '!=', '')->take(10)->get();
        $flights_update = substr(OverflightUpdate::first()->updated_at, -8, 5);

        return view('site.home')->with('clt_twr', $clt_twr)->with('atl_twr', $atl_twr)->with('atl_app', $atl_app)->with('atl_ctr', $atl_ctr)
                                ->with('airports', $airports)->with('metar_last_updated', $metar_last_updated)
                                ->with('controllers', $controllers)->with('controllers_update', $controllers_update)
                                ->with('calendar', $calendar)->with('news', $news)->with('events', $events)
                                ->with('flights', $flights)->with('flights_update', $flights_update);
    }
   
    public function showStats($year = null, $month = null) {
        if ($year == null)
            $year = date('y');

        if ($month == null)
            $month = date('n');

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->get();
        $agreevisitc = User::where('visitor', 1)->where('visitor_from', 'ZHU')->orWhere('visitor_from', 'ZJX')->where('status', 1)->get();
       
        
        $home = $homec->sortByDesc(function($user) use($stats) {
            return $stats[$user->id]->total_hrs;
        });

        $visit = $visitc->sortByDesc(function($user) use($stats) {
            return $stats[$user->id]->total_hrs;
        });

        $agreevisit = $agreevisitc->sortByDesc(function($user) use($stats) {
            return $stats[$user->id]->total_hrs;
        });

        return view('site.stats')->with('all_stats', $all_stats)->with('year', $year)
                                 ->with('month', $month)->with('stats', $stats)
                                 ->with('home', $home)->with('visit', $visit)->with('agreevisit', $agreevisit);
    }
    
    public function new_look() {
        return view('dashboard.new_look.index');
    }
    public function teamspeak() {
        return view('site.teamspeak');
    }

    public function showFiles() {
        $vrc = File::where('type', 0)->orderBy('name', 'ASC')->get();
        $vstars = File::where('type', 1)->orderBy('name', 'ASC')->get();
        $veram = File::where('type', 2)->orderBy('name', 'ASC')->get();
        $vatis = File::where('type', 3)->orderBy('name', 'ASC')->get();
        $sop = File::where('type', 4)->orderBy('name', 'ASC')->get();
        $loa = File::where('type', 5)->orderBy('name', 'ASC')->get();

        return view('site.files')->with('vrc', $vrc)->with('vstars', $vstars)->with('veram', $veram)->with('vatis', $vatis)->with('sop', $sop)->with('loa', $loa);
    }

    public function sceneryIndex(Request $request) {
        if($request->search == null) {
            $scenery = Scenery::orderBy('airport', 'ASC')->get();
        } else {
            $scenery = Scenery::where('airport', $request->search)->orWhere('developer', $request->search)->orderBy('airport', 'ASC')->get();
        }

        $fsx = $scenery->where('sim', 0);
        $xp = $scenery->where('sim', 1);
        $afcad = $scenery->where('sim', 2);

        return view('site.scenery.index')->with('fsx', $fsx)->with('xp', $xp)->with('afcad', $afcad);
    }

    public function searchScenery(Request $request) {
        return redirect('/pilots/scenery?search='.$request->search);
    }

    public function showScenery($id) {
        $scenery = Scenery::find($id);

        return view('site.scenery.show')->with('scenery', $scenery);
    }
    public function showStaffRequest() {
        return view('site.request_staffing');
    }

    public function staffRequest(Request $request) {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'date' => 'required',
            'time' => 'required',
            'additional_information' => 'required'
        ]);

        //Google reCAPTCHA Verification
        $client = new Client;
        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => Config::get('google.recaptcha'),
                'response' => $request->input('g-recaptcha-response'),
            ]
        ]);
        $r = json_decode($response->getBody())->success;
        if($r != true) {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $name = $request->name;
        $email = $request->email;
        $org = $request->org;
        $date = $request->date;
        $time = $request->time;
        $exp = $request->additional_information;

        Mail::send('emails.request_staff', ['name' => $name, 'email' => $email, 'org' => $org, 'date' => $date, 'time' => $time, 'exp' => $exp], function($message) use ($email, $name, $date) {
            $message->from('info@notams.ztlartcc.org', 'vZTL ARTCC Staffing Requests')->subject('New Staffing Request for '.$date);
            $message->to('ec@ztlartcc.org')->replyTo($email, $name);
        });

        return redirect('/')->with('success', 'The staffing request has been delivered to the appropiate parties successfully. You should expect to hear back soon.');
    }
}