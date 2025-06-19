<?php

namespace App\Http\Controllers;

use App\Airport;
use App\ATC;
use App\AtcBooking;
use App\Calendar;
use App\ControllerLog;
use App\Event;
use App\Feedback;
use App\File;
use App\LiveEvent;
use App\Mail\ReqStaffing;
use App\Mail\VisitorMail;
use App\Overflight;
use App\Scenery;
use App\TrainerFeedback;
use App\User;
use App\Visitor;
use Carbon\Carbon;
use Config;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mail;

class FrontController extends Controller {
    public function home() {
        $atc = ATC::get();

        $fields = [];
        $center = 0;
        $field =null;
        if ($atc) {
            foreach (monitoredAirfields() as $id => $name) {
                $fields[$id] = [
                    'name' => $name,
                    'approach' => [
                        'online' => 0
                    ],
                    'online' => 0,
                    'subfields' => []
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
                    continue;
                }

                if ($position == 'APP' || $position == 'DEP') {
                    $fields[$field]['approach'] = [
                        'online' => 1
                    ];
                    continue;
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
            if (isset($fields[$field])) {
                rsort($fields[$field]['subfields']);
            }
        }

        $airports = Airport::where('front_pg', 1)->orderBy('ltr_4', 'ASC')->get();
        $controllers = ATC::get();

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

        $today = Carbon::today();
        $bookings = AtcBooking::whereDate('start', '>=', $today)
            ->whereDate('end', '<=', $today->addDays(14))
            ->orderBy('start', 'ASC')
            ->get();

        $bookings = groupAtcBookingsByDate($bookings);

        $pilot_accomplishments = PilotPassportController::fetchRecentPilotAccomplishments();

        return view('site.home')->with('center', $center)->with('fields', $fields)
                                ->with('airports', $airports)->with('controllers', $controllers)
                                ->with('calendar', $calendar)->with('news', $news)->with('events', $events)
                                ->with('overflightCount', $overflightCount)
                                ->with('bookings', $bookings)
                                ->with('pilot_accomplishments', $pilot_accomplishments);
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

    public function showAirport($id) {
        $airport = Airport::find($id);

        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', 'https://api-v2.aviationapi.com/v2/charts?airport='.$airport->ltr_4);
        $status = $res->getStatusCode();
        if ($status == 404) {
            $charts = null;
        } elseif (json_decode($res->getBody()) != '[]') {
            $apt_r = $airport->ltr_4;
            $charts = collect(json_decode($res->getBody())->charts);
            $general = $charts["general"];
            $apd = $charts["airport_diagram"];
            $iap = $charts["approach"];
            $dp = $charts["departure"];
            $star = $charts["arrival"];
        } else {
            $charts = null;
        }

        return view('site.airports.view')->with('airport', $airport)
                                         ->with('charts', $charts)->with('general', $general)->with('apd', $apd)->with('iap', $iap)->with('dp', $dp)->with('star', $star);
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
        $qtr_stats = ControllerLog::aggregateAllControllersByPosAndQuarter($year, $month);
        $all_stats = ControllerLog::getAllControllerStats();

        $hcontrollers_public = User::where('visitor', '0')->where('status', '1')->where('name_privacy', '0')->orderBy('lname', 'ASC')->get();
        $hcontrollers_private = User::where('visitor', '0')->where('status', '1')->where('name_privacy', '1')->orderBy('id', 'ASC')->get();
        $homec = $hcontrollers_public->merge($hcontrollers_private);
        $vcontrollers_public = User::where('visitor', '1')->where('status', '1')->where('name_privacy', '0')->orderBy('lname', 'ASC')->get();
        $vcontrollers_private = User::where('visitor', '1')->where('status', '1')->where('name_privacy', '1')->orderBy('id', 'ASC')->get();
        $visitc = $vcontrollers_public->merge($vcontrollers_private);
        
        $home = $homec->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->total_hrs;
        });

        $visit = $visitc->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->total_hrs;
        });

        return view('site.stats')->with('all_stats', $all_stats)->with('year', $year)
                                 ->with('month', $month)->with('stats', $stats)->with('qtr_stats', $qtr_stats)
                                 ->with('home', $home)->with('visiting', $visit);
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
        if ($r != true && Config::get('app.env') != 'local') {
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
                    ]
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
                    
            Mail::to($visit->email)->cc('datm@ztlartcc.org')->send(new VisitorMail('new', $visit));
        
            return redirect('/')->with('success', 'Thank you for your interest in the ZTL ARTCC! Your visit request has been submitted.');
        } else {
            return redirect('/')->with('error', 'You need to be a S1 rated controller or greater');
        }
    }

    public function newFeedback($controllerSelected=null) {
        $feedbackOptions = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        $feedbackCIDs = User::where('status', 1)->orderBy('id', 'ASC')->get()->pluck('id');
        foreach ($feedbackCIDs as $feedbackCID) {
            $feedbackOptions->put('c' . $feedbackCID, $feedbackCID);
        }
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
        if ($r != true && Config::get('app.env') != 'local') {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $feedback = new Feedback;
        $feedback->feedback_id = ltrim($request->input('feedback_id'), 'gec');
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

    public function newTrainerFeedback() {
        return view('site.trainer_feedback')->with('feedbackOptions', TrainerFeedback::getFeedbackOptions());
    }

    public function showFiles() {
        $vatis = File::where('type', 3)->orderBy('disp_order', 'ASC')->get();
        $sop = File::where('type', 4)->orderBy('disp_order', 'ASC')->get();
        $loa = File::where('type', 5)->orderBy('disp_order', 'ASC')->get();

        return view('site.files')->with('vatis', $vatis)->with('sop', $sop)->with('loa', $loa);
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
        if ($r != true && Config::get('app.env') != 'local') {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $name = $request->name;
        $email = $request->email;
        $org = $request->org;
        $date = $request->date;
        $time = $request->time;
        $exp = $request->additional_information;

        Mail::to('ec@ztlartcc.org')->send(new ReqStaffing($name, $email, $org, $date, $time, $exp));

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

        $client = new Client(['http_errors' => false]);
        $icao_id = 'KATL';
        $res = $client->request('GET', 'https://api-v2.aviationapi.com/v2/charts?airport=' . $icao_id);
        $status = $res->getStatusCode();
        $diag = $aaup = '#';
        $charts = null;
        if ($status == 200 && json_decode($res->getBody()) != '[]') {
            $charts = collect(json_decode($res->getBody())->charts);
            $diag = $charts['airport_diagram'];
            if (count($diag)>0) {
                $diag = $diag[0]->pdf_url;
            } else {
                $diag = '#';
            }
            $aaup = null;
            foreach ($charts['departure'] as $chart) {
                if ($chart->chart_name == 'RNAV DP AAUP') {
                    $aaup = $chart;
                    break;
                }
            }
            if (isset($aaup)) {
                $aaup = $aaup->pdf_url;
            } else {
                $aaup = 'https://vats.im/ATLDPAAUP';
            }
        } else {
            $charts = null;
        }
        
        return view('site.pilot_guide_atl')->with('controllers', $lcl_controllers)
        ->with('diag', $diag)->with('aaup', $aaup);
    }

    public function showLiveEventInfo() {
        $live_event = LiveEvent::getAnnouncement();
        return view('site.live_event_info')->with('liveEventInfo', $live_event);
    }
}
