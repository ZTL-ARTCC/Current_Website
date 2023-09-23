<?php

namespace App\Http\Controllers;

use App\Airport;
use App\ATC;
use App\Calendar;
use App\ControllerLog;
use App\ControllerLogUpdate;
use App\Event;
use App\Feedback;
use App\File;
use App\Metar;
use App\Overflight;
use App\Scenery;
use App\User;
use App\Visitor;
use Carbon\Carbon;
use Config;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mail;
use SimpleXMLElement;

class FrontController extends Controller {
    public function home() {
        $atc = ATC::get();

        // For each facility (i.e. ATL), we want:
        // Approach
        // Departure
        // Tower (T, G, D, A)

        $fields = [];
        $center = 0;

        if ($atc) {
            foreach (monitoredAirfields() as $id => $name) {
                $fields[$id] = [
                    'name' => $name,
                    'approach' => [
                        'online' => 0
                    ],
                    'online' => 0,
                    'subfields' => [
                        /*
                             * [
                             *   'online' => 1,
                             *   'short' => 'T',
                             *   'color' => 'bg-tower'
                             * ]
                             */
                    ]
                ];
            }

            foreach ($atc as $a) {
                $field = substr($a->position, 0, 3);
                $position = substr($a->position, -3);

                if (!isMonitoredAirfield($field)) {
                    continue;
                }

                if ($field == 'ATL' && $position == 'CTR') {
                    $center = 1;
                    continue; // Atlanta Center is not a 'field' persay, so it gets it's own variable.
                }

                if ($position == 'APP' || $position == 'DEP') {
                    $fields[$field]['approach'] = [
                        'online' => 1
                    ];
                    continue; // approach and departure
                }

                if ($position == 'TWR' || $position == 'GND' || $position == 'DEL') {
                    $fields[$field]['online'] = 1;
                    $fields[$field]['subfields'][$position] = [
                        'online' => 1,
                        'short' => substr($position, 0, 1),
                        'color' => 'pos'.$position
                    ];
                }
            }
        }

        $airports = Airport::where('front_pg', 1)->orderBy('ltr_4', 'ASC')->get();
        $metar_update = Metar::first();
        if ($metar_update != null) {
            $metar_last_updated = substr($metar_update, -18, 5);
        } else {
            $metar_last_updated = null;
        }

        $controllers = ATC::get();
        $last_update = ControllerLogUpdate::orderBy('id', 'desc')->first();
        $controllers_update = substr($last_update->created_at, -8, 5);

        $now = Carbon::now();

        $calendar = Calendar::where('type', '1')->get()->filter(function ($news) use ($now) {
            return strtotime($news->date.' '.$news->time) > strtotime($now);
        })->sortBy(function ($news) {
            return strtotime($news->date.' '.$news->time);
        });

        $news = Calendar::where('type', '2')->where('visible', '1')->get()->filter(function ($news) use ($now) {
            return strtotime($news->date.' '.$news->time) < strtotime($now);
        })->sortByDesc(function ($news) {
            return strtotime($news->date.' '.$news->time);
        });

        $events = Event::fetchVisibleEvents();
        foreach ($events as $e) {
            $e->banner_path = $e->displayBannerPath();
            $e->forum = 'board=8.0';
            if (is_numeric($e->id_topic)) {
                $e->forum = 'topic=' . $e->id_topic;
            }
        }

        $overflightCount = Overflight::where('dep', '!=', '')->where('arr', '!=', '')->count();

        return view('site.home')->with('center', $center)->with('fields', $fields)
                                ->with('airports', $airports)->with('metar_last_updated', $metar_last_updated)
                                ->with('controllers', $controllers)->with('controllers_update', $controllers_update)
                                ->with('calendar', $calendar)->with('news', $news)->with('events', $events)
                                ->with('overflightCount', $overflightCount);
    }

    public function teamspeak() {
        return view('site.teamspeak');
    }
    
     public function privacy() {
         return view('site.privacy');
     }

    public function airportIndex() {
        $airports = Airport::orderBy('ltr_3', 'ASC')->get();
        return view('site.airports.index')->with('airports', $airports);
    }

    public function searchAirport(Request $request) {
        $apt = $request->apt;
        return redirect('/pilots/airports/search?apt='.$apt);
    }

    public function searchAirportResult(Request $request) {
        $apt = $request->apt;
        if (strlen($apt) == 3) {
            $apt_s = 'k'.strtolower($apt);
        } elseif (strlen($apt) == 4) {
            $apt_s = strtolower($apt);
        } else {
            return redirect()->back()->with('error', 'You either did not search for an airport or the airport ID is too long.');
        }

        $apt_r = strtoupper($apt_s);

        $client = new Client;
        $response_metar = $client->request('GET', 'https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=metars&requestType=retrieve&format=xml&hoursBeforeNow=2&mostRecentForEachStation=true&stationString='.$apt_s);
        $response_taf = $client->request('GET', 'https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=tafs&requestType=retrieve&format=xml&hoursBeforeNow=2&mostRecentForEachStation=true&stationString='.$apt_s);

        $root_metar = new SimpleXMLElement($response_metar->getBody());
        $root_taf = new SimpleXMLElement($response_taf->getBody());

        $metar = $root_metar->data->children()->METAR->raw_text;
        $taf = $root_taf->data->children()->TAF->raw_text;

        if ($metar == null) {
            return redirect()->back()->with('error', 'The airport code you entered is invalid.');
        }
        $metar = $metar->__toString();
        if ($taf != null) {
            $taf = $taf->__toString();
        }
        $visual_conditions = $root_metar->data->children()->METAR->flight_category->__toString();

        $pilots_a = $pilots_d = false;
        $res_a = $client->get('https://ids.ztlartcc.org/FetchAirportInfo.php?id='.$apt_s.'&type=arrival');
        $pilots_a = json_decode($res_a->getBody()->getContents(), true);

        if ($pilots_a) {
            $pilots_a = collect($pilots_a);
        } else {
            $pilots_a = null;
        }
        $res_d = $client->get('https://ids.ztlartcc.org/FetchAirportInfo.php?id='.$apt_s.'&type=departure');
        $pilots_d = json_decode($res_d->getBody()->getContents(), true);

        if ($pilots_d) {
            $pilots_d = collect($pilots_d);
        } else {
            $pilots_d = null;
        }

        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', 'https://api.aviationapi.com/v1/charts?apt='.$apt_r);
        $status = $res->getStatusCode();
        if ($status == 404) {
            $charts = null;
        } elseif (json_decode($res->getBody()) != '[]') {
            $charts = collect(json_decode($res->getBody())->$apt_r);
            $min = $charts->where('chart_code', 'MIN');
            $hot = $charts->where('chart_code', 'HOT');
            $lah = $charts->where('chart_code', 'LAH');
            $apd = $charts->where('chart_code', 'APD');
            $iap = $charts->where('chart_code', 'IAP');
            $dp = $charts->where('chart_code', 'DP');
            $star = $charts->where('chart_code', 'STAR');
            $cvfp = $charts->where('chart_code', 'CVFP');
        } else {
            $charts = null;
        }

        return view('site.airports.search')->with('apt_r', $apt_r)->with('metar', $metar)->with('taf', $taf)->with('visual_conditions', $visual_conditions)->with('pilots_a', $pilots_a)->with('pilots_d', $pilots_d)
                                           ->with('charts', $charts)->with('min', $min)->with('hot', $hot)->with('lah', $lah)->with('apd', $apd)->with('iap', $iap)->with('dp', $dp)->with('star', $star)->with('cvfp', $cvfp);
    }

    public function showAirport($id) {
        $airport = Airport::find($id);

        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', 'https://api.aviationapi.com/v1/charts?apt='.$airport->ltr_4);
        $status = $res->getStatusCode();
        if ($status == 404) {
            $charts = null;
        } elseif (json_decode($res->getBody()) != '[]') {
            $apt_r = $airport->ltr_4;
            $charts = collect(json_decode($res->getBody())->$apt_r);
            $min = $charts->where('chart_code', 'MIN');
            $hot = $charts->where('chart_code', 'HOT');
            $lah = $charts->where('chart_code', 'LAH');
            $apd = $charts->where('chart_code', 'APD');
            $iap = $charts->where('chart_code', 'IAP');
            $dp = $charts->where('chart_code', 'DP');
            $star = $charts->where('chart_code', 'STAR');
            $cvfp = $charts->where('chart_code', 'CVFP');
        } else {
            $charts = null;
        }

        return view('site.airports.view')->with('airport', $airport)
                                         ->with('charts', $charts)->with('min', $min)->with('hot', $hot)->with('lah', $lah)->with('apd', $apd)->with('iap', $iap)->with('dp', $dp)->with('star', $star)->with('cvfp', $cvfp);
    }

    public function sceneryIndex(Request $request) {
        if ($request->search == null) {
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

    public function showStats($year = null, $month = null) {
        if ($year == null) {
            $year = date('y');
        }

        if ($month == null) {
            $month = date('n');
        }

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->get();
        $agreevisitc = User::where('visitor', 1)->where('visitor_from', 'ZHU')->orWhere('visitor_from', 'ZJX')->where('status', 1)->get();
       
        
        $home = $homec->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->total_hrs;
        });

        $visit = $visitc->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->total_hrs;
        });

        $agreevisit = $agreevisitc->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->total_hrs;
        });

        return view('site.stats')->with('all_stats', $all_stats)->with('year', $year)
                                 ->with('month', $month)->with('stats', $stats)
                                 ->with('home', $home)->with('visit', $visit)->with('agreevisit', $agreevisit);
    }

    public function visit() {
        return view('site.visit');
    }

    public function storeVisit(Request $request) {
        $validator = $request->validate([
            'cid' => 'required',
            'name' => 'required',
            'email' => 'required',
            'rating' => 'required',
            'home' => 'required',
            'reason' => 'required'
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
        if ($r != true) {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }
        
        // Check to see if CID is already active in database (prevents account takeover)
        if (User::find($request->cid) !== null) {
            $user = User::find($request->cid);
            if ($user->status == 1) {
                return redirect()->back()->with('error', 'Unable to apply as a visitor - you are already listed as a controller on our roster. If you believe this is in error, contact the ZTL DATM at datm@ztlartcc.org');
            }
        }
        
        if ($request->rating != 1) {
            //Continue Request
            /*  $visit = new Visitor;

              $visit->cid = $request->cid;
              $visit->name = $request->name;
              $visit->email = $request->email;
              $visit->rating = $request->rating;
              $visit->home = $request->home;
              $visit->reason = $request->reason;
              $visit->status = 0;
              $visit->save();*/
            
            $expireDate = new DateTime($request->updated_at);
            $expireDate->modify('+ 90 days');
            if (date_create() > $expireDate) {
                $visit = Visitor::Create(
                    ['cid' => $request->cid,
                    'name' => $request->name,
                        'email' => $request->email,
                        'rating' => $request->rating,
                        'home' => $request->home,
                        'reason'=> $request->reason,
                        'status'=> 0
                    ]//
                );
            } else {
                $visit = Visitor::updateOrCreate(
                    ['cid' => $request->cid],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'rating' => $request->rating,
                        'home' => $request->home,
                        'reason'=> $request->reason,
                        'status'=> 0
                    ]
                );
            }
                    
        

            Mail::send('emails.visit.new', ['visit' => $visit], function ($message) use ($visit) {
                $message->from('visitors@notams.ztlartcc.org', 'ZTL Visiting Department')->subject('New Visitor Request Submitted');
                $message->to($visit->email)->cc('datm@ztlartcc.org');
            });
        
            return redirect('/')->with('success', 'Thank you for your interest in the ZTL ARTCC! Your visit request has been submitted.');
        } else {
            return redirect('/')->with('error', 'You need to be a S1 rated controller or greater');
        }
    }

    public function newFeedback($controllerSelected=null) {
        $feedbackOptions = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        if (!is_null($controllerSelected)&&array_key_exists($controllerSelected, $feedbackOptions->all())) {
            $controllerSelected = intval($controllerSelected);
        }
        $events = Event::all()->pluck('date', 'id');
        $eventsList = [];
        foreach ($events as $evId => $event) {
            $eventDate = Carbon::createFromFormat('m/d/Y', substr($event, 0, 10));
            if ((Carbon::today() >= $eventDate) && (Carbon::today() <= $eventDate->copy()->addDays(30))) {
                $eventsList[] = $evId;
            }
        }
        unset($events);
        $events = Event::whereIn('id', $eventsList)->orderBy('name', 'DESC')->get()->pluck('name', 'id');
        foreach ($events as $evId => $event) {
            $events['e' . $evId] = $event;
            unset($events[$evId]);
            $feedbackOptions->prepend('Event: ' . $event, 'e' . $evId);
        }
        $feedbackOptions->prepend('General ATC Feedback', 'g0');
        return view('site.feedback')->with('feedbackOptions', $feedbackOptions)->with('controllerSelected', $controllerSelected);
    }

    public function saveNewFeedback(Request $request) {
        $validatedData = $request->validate([
            'feedback_id' => 'required',
            'position' => 'required',
            'callsign' => 'required',
            'pilot_name' => 'required',
            'pilot_email' => 'required',
            'pilot_cid' => 'required',
            'comments' => 'required'
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
        if ($r != true) {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $feedback = new Feedback;
        $feedback->feedback_id = ltrim($request->input('feedback_id'), 'ge');
        $feedback->position = $request->input('position');
        $feedback->service_level = $request->input('service');
        $feedback->callsign = $request->input('callsign');
        $feedback->pilot_name = $request->input('pilot_name');
        $feedback->pilot_email = $request->input('pilot_email');
        $feedback->pilot_cid = $request->input('pilot_cid');
        $feedback->comments = $request->input('comments');
        $feedback->status = 0;
        $feedback->save();

        return redirect('/')->with('success', 'Thank you for the feedback! It has been received successfully.');
    }

    public function showFiles() {
        $vrc = File::where('type', 0)->orderBy('disp_order', 'ASC')->get();
        $vstars = File::where('type', 1)->orderBy('disp_order', 'ASC')->get();
        $veram = File::where('type', 2)->orderBy('disp_order', 'ASC')->get();
        $vatis = File::where('type', 3)->orderBy('disp_order', 'ASC')->get();
        $sop = File::where('type', 4)->orderBy('disp_order', 'ASC')->get();
        $loa = File::where('type', 5)->orderBy('disp_order', 'ASC')->get();

        return view('site.files')->with('vrc', $vrc)->with('vstars', $vstars)->with('veram', $veram)->with('vatis', $vatis)->with('sop', $sop)->with('loa', $loa);
    }
    
    public function showPermalink($slug) {
        $file = File::where('permalink', $slug)->first();
        if (!is_null($file)) {
            return redirect($file->path);
        } else {
            return redirect('/')->with('error', 'The requested resource is not available.');
        }
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
        if ($r != true) {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $name = $request->name;
        $email = $request->email;
        $org = $request->org;
        $date = $request->date;
        $time = $request->time;
        $exp = $request->additional_information;

        Mail::send('emails.request_staff', ['name' => $name, 'email' => $email, 'org' => $org, 'date' => $date, 'time' => $time, 'exp' => $exp], function ($message) use ($email, $name, $date) {
            $message->from('info@notams.ztlartcc.org', 'vZTL ARTCC Staffing Requests')->subject('New Staffing Request for '.$date);
            $message->to('ec@ztlartcc.org')->replyTo($email, $name);
        });

        return redirect('/')->with('success', 'The staffing request has been delivered to the appropiate parties successfully. You should expect to hear back soon.');
    }
    
    public function showAtlRamp() {
        return view('site.ramp')->with('afld', 'ATL');
    }
    
    public function showCltRamp() {
        return view('site.ramp')->with('afld', 'CLT');
    }

    public function pilotGuideAtl() {
        $atc = ATC::get();
        if ($atc) {
            $lcl_controllers = [];
            $app_controllers = [];
            $ctr_controllers = [];
            foreach ($atc as $a) {
                $field = substr($a->position, 0, 3);
                $position = substr($a->position, -3);
                if ((($field == 'ATL')||($field == 'ZTL'))&&($a->freq != '199.998')) {
                    if ($position == 'TWR' || $position == 'GND' || $position == 'DEL') {
                        $lcl_controllers[] = $a;
                    } elseif ($position == 'APP' || $position == 'DEP') {
                        $app_controllers[] = $a;
                    } elseif ($position == 'CTR') {
                        $ctr_controllers[] = $a;
                    }
                }
            }
        }
        if (count($lcl_controllers) < 1) {
            $lcl_controllers = $app_controllers;
            if (count($lcl_controllers) < 1) {
                $lcl_controllers = $ctr_controllers;
            }
        }
        $last_update = ControllerLogUpdate::orderBy('id', 'desc')->first();
        $controllers_update = substr($last_update->created_at, -8, 5);

        $client = new Client(['http_errors' => false]);
        $icao_id = 'KATL';
        $res = $client->request('GET', 'https://api.aviationapi.com/v1/charts?apt=' . $icao_id);
        $status = $res->getStatusCode();
        $diag = $aaup = '#';
        $charts = null;
        if ($status == 200 && json_decode($res->getBody()) != '[]') {
            $charts = collect(json_decode($res->getBody())->$icao_id);
            $diag = $charts->where('chart_code', 'APD');
            if (count($diag)>0) {
                $diag = $diag->first()->pdf_path;
            } else {
                $diag = '#';
            }
            $aaup = $charts->where('chart_code', 'DAU');
            if (count($aaup)>0) {
                $aaup = $aaup->first()->pdf_path;
            } else {
                $aaup = '#';
            }
        } else {
            $charts = null;
        }
        
        return view('site.pilot_guide_atl')->with('controllers', $lcl_controllers)->with('controllers_update', $controllers_update)
        ->with('diag', $diag)->with('aaup', $aaup);
    }
}
