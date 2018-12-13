<?php

namespace App\Http\Controllers;

use App\Airport;
use App\ATC;
use App\Bronze;
use App\Calendar;
use App\ControllerLog;
use App\ControllerLogUpdate;
use App\Event;
use App\Feedback;
use App\File;
use App\Http\Controllers\toArray;
use App\Metar;
use App\Permission;
use App\Role;
use App\Scenery;
use App\User;
use App\Visitor;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mail;
use Response;
use SimpleXMLElement;
use Validation;

class FrontController extends Controller
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
                    if($position == 'TWR' || $position == 'GND') {
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
        $news = Calendar::where('type', '2')->get()->filter(function($news) use ($now) {
            return strtotime($news->date.' '.$news->time) < strtotime($now);
        })->sortByDesc(function($news) {
            return strtotime($news->date.' '.$news->time);
        });
        $events = Event::where('status', 1)->get()->sortBy(function($e) {
            return strtotime($e->date);
        });

        return view('site.home')->with('clt_twr', $clt_twr)->with('atl_twr', $atl_twr)->with('atl_app', $atl_app)->with('atl_ctr', $atl_ctr)
                                ->with('airports', $airports)->with('metar_last_updated', $metar_last_updated)
                                ->with('controllers', $controllers)->with('controllers_update', $controllers_update)
                                ->with('calendar', $calendar)->with('news', $news)->with('events', $events);
    }

    public function teamspeak() {
        return view('site.teamspeak');
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
        if(strlen($apt) == 3) {
            $apt_s = 'k'.strtolower($apt);
        } elseif(strlen($apt) == 4) {
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

        if($metar == null) {
            return redirect()->back()->with('error', 'The airport code you entered is invalid.');
        }
        $metar = $metar->__toString();
        if($taf != null) {
            $taf = $taf->__toString();
        }
        $visual_conditions = $root_metar->data->children()->METAR->flight_category->__toString();

        $res_a = $client->get('http://api.vateud.net/online/arrivals/'.$apt_s.'.json');
        $pilots_a = json_decode($res_a->getBody()->getContents(), true);

        if($pilots_a) {
            $pilots_a = collect($pilots_a);
        } else {
            $pilots_a = null;
        }

        $res_d = $client->get('http://api.vateud.net/online/departures/'.$apt_s.'.json');
        $pilots_d = json_decode($res_d->getBody()->getContents(), true);

        if($pilots_d) {
            $pilots_d = collect($pilots_d);
        } else {
            $pilots_d = null;
        }

        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', 'https://api.aircharts.org/v2/Airport/'.$apt_r);
        $status = $res->getStatusCode();
        if($status == 404) {
            $charts = null;
        } elseif(isset(json_decode($res->getBody())->$apt_r->charts) == true) {
            $charts = json_decode($res->getBody())->$apt_r->charts;
        } else {
            $charts = null;
        }

        return view('site.airports.search')->with('apt_r', $apt_r)->with('metar', $metar)->with('taf', $taf)->with('visual_conditions', $visual_conditions)->with('pilots_a', $pilots_a)->with('pilots_d', $pilots_d)->with('charts', $charts);
    }

    public function showAirport($id) {
        $airport = Airport::find($id);

        return view('site.airports.view')->with('airport', $airport);
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

    public function showStats($year = null, $month = null) {
        if ($year == null)
            $year = date('y');

        if ($month == null)
            $month = date('n');

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->get();

        $home = $homec->sortByDesc(function($user) use($stats) {
            return $stats[$user->id]->total_hrs;
        });

        $visit = $visitc->sortByDesc(function($user) use($stats) {
            return $stats[$user->id]->total_hrs;
        });

        return view('site.stats')->with('all_stats', $all_stats)->with('year', $year)
                                 ->with('month', $month)->with('stats', $stats)
                                 ->with('home', $home)->with('visit', $visit);
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
        if($r != true) {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $visit = new Visitor;
        $visit->cid = $request->cid;
        $visit->name = $request->name;
        $visit->email = $request->email;
        $visit->rating = $request->rating;
        $visit->home = $request->home;
        $visit->reason = $request->reason;
        $visit->status = 0;
        $visit->save();

        Mail::send('emails.visit.new', ['visit' => $visit], function($message) use ($visit){
            $message->from('visitors@notams.ztlartcc.org', 'ZTL Visiting Department')->subject('New Visitor Request Submitted');
            $message->to($visit->email)->cc('datm@ztlartcc.org');
        });

        return redirect('/')->with('success', 'Thank you for your interest in the ZTL ARTCC! Your visit request has been submitted.');
    }

    public function newFeedback() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        return view('site.feedback')->with('controllers', $controllers);
    }

    public function saveNewFeedback(Request $request) {
        $validatedData = $request->validate([
            'controller' => 'required',
            'position' => 'required',
            'callsign' => 'required'
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
        $feedback = new Feedback;
        $feedback->controller_id = Input::get('controller');
        $feedback->position = Input::get('position');
        $feedback->service_level = Input::get('service');
        $feedback->callsign = Input::get('callsign');
        $feedback->pilot_name = Input::get('pilot_name');
        $feedback->pilot_email = Input::get('pilot_email');
        $feedback->pilot_cid = Input::get('pilot_cid');
        $feedback->comments = Input::get('comments');
        $feedback->status = 0;
        $feedback->save();

        return redirect('/')->with('success', 'Thank you for the feedback! It has been recieved successfully.');
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

    public function downloadFile($id) {
        $file = File::find($id);
        $file_path = $file->path;

        return Response::download($file_path);
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
