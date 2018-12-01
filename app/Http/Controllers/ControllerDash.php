<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\ATC;
use App\Bronze;
use App\Calendar;
use App\ControllerLog;
use App\ControllerLogUpdate;
use App\Event;
use App\EventPosition;
use App\EventRegistration;
use App\Feedback;
use App\File;
use App\Opt;
use App\PositionPreset;
use App\Scenery;
use App\TrainingTicket;
use App\User;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleXMLElement;
use Response;

class ControllerDash extends Controller
{
    public function dash() {
        $calendar = Calendar::where('type', '1')->orderBy('date', 'DSC')->orderBy('time', 'DSC')->get();
        $news = Calendar::where('type', '2')->orderBy('date', 'DSC')->orderBy('time', 'DSC')->get();
        $announcement = Announcement::find(1);

        $now = Carbon::now();

        $nmonth = $now->month;
        $year = substr($now->year, -2);
        $month = $nmonth - '1';
        $pmonth = $month - '1';

        if($month == 1) {
            $month_words = 'January';
        } elseif($month == 2) {
            $month_words = 'Febuary';
        } elseif($month == 3) {
            $month_words = 'March';
        } elseif($month == 4) {
            $month_words = 'April';
        } elseif($month == 5) {
            $month_words = 'May';
        } elseif($month == 6) {
            $month_words = 'June';
        } elseif($month == 7) {
            $month_words = 'July';
        } elseif($month == 8) {
            $month_words = 'August';
        } elseif($month == 9) {
            $month_words = 'September';
        } elseif($month == 10) {
            $month_words = 'October';
        } elseif($month == 11) {
            $month_words = 'November';
        } elseif($month == 12 || $month == 0) {
            $month_words = 'December';
        }

        if($pmonth == 1) {
            $pmonth_words = 'January';
        } elseif($pmonth == 2) {
            $pmonth_words = 'Febuary';
        } elseif($pmonth == 3) {
            $pmonth_words = 'March';
        } elseif($pmonth == 4) {
            $pmonth_words = 'April';
        } elseif($pmonth == 5) {
            $pmonth_words = 'May';
        } elseif($pmonth == 6) {
            $pmonth_words = 'June';
        } elseif($pmonth == 7) {
            $pmonth_words = 'July';
        } elseif($pmonth == 8) {
            $pmonth_words = 'August';
        } elseif($pmonth == 9) {
            $pmonth_words = 'September';
        } elseif($pmonth == 10) {
            $pmonth_words = 'October';
        } elseif($pmonth == 11 || $month == -1) {
            $pmonth_words = 'November';
        } elseif($pmonth == 12 || $month == 0) {
            $pmonth_words = 'December';
        }

        if($month < 1) {
            $year = substr($now->year, -2) - '1';
        } else {
            $year = substr($now->year, -2);
        }
        $winner = Bronze::where('month', $month)->where('year', $year)->first();

        if($pmonth < 1) {
            $pyear = substr($now->year, -2) - '1';
        } else {
            $pyear = substr($now->year, -2);
        }
        $pwinner = Bronze::where('month', $pmonth)->where('year', $pyear)->first();

        $controllers = ATC::get();
        $last_update = ControllerLogUpdate::first();
        $controllers_update = substr($last_update->created_at, -8, 5);
        $events = Event::where('status', 1)->orderBy('date', 'ASC')->get();

        return view('dashboard.dashboard')->with('calendar', $calendar)->with('news', $news)->with('announcement', $announcement)
                                          ->with('winner', $winner)->with('pwinner', $pwinner)->with('month_words', $month_words)->with('pmonth_words', $pmonth_words)
                                          ->with('controllers', $controllers)->with('controllers_update', $controllers_update)
                                          ->with('events', $events);
    }

    public function showProfile($year = null, $month = null) {
        if ($year == null)
            $year = date('y');

        if ($month == null)
            $month = date('n');

        $user_id = Auth::id();
        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $feedback = Feedback::where('controller_id', $user_id)->where('status', 1)->orderBy('updated_at', 'ASC')->get();
        $personal_stats = $stats[$user_id];
        $tickets = TrainingTicket::where('controller_id', Auth::id())->orderBy('date', 'DSC')->orderBy('start_time', 'DSC')->paginate('10');

        $last_training = TrainingTicket::where('controller_id', Auth::id())->orderBy('date', 'DSC')->orderBy('start_time', 'DSC')->first();
        $last_training_given = TrainingTicket::where('trainer_id', Auth::id())->orderBy('date', 'DSC')->orderBy('start_time', 'DSC')->first();

        return view('dashboard.controllers.profile')->with('personal_stats', $personal_stats)->with('feedback', $feedback)->with('tickets', $tickets)->with('last_training', $last_training)->with('last_training_given', $last_training_given);
    }

    public function showTicket($id) {
        $ticket = TrainingTicket::find($id);
        if(Auth::id() == $ticket->controller_id) {
            return view('dashboard.controllers.ticket')->with('ticket', $ticket);
        } else {
            return redirect()->back()->with('error', 'You can only view your own tickets. If you are a trainer trying to view a ticket, please do that from the training section.');
        }
    }

    public function showTeamspeak() {
        return view('dashboard.controllers.teamspeak');
    }

    public function showRoster() {
        $hcontrollers = User::where('visitor', '0')->where('status', '1')->orWhere('status', '0')->orderBy('lname', 'ASC')->get();
        $vcontrollers = User::where('visitor', '1')->where('status', '1')->orderBy('lname', 'ASC')->get();

        return view('dashboard.controllers.roster')->with('hcontrollers', $hcontrollers)->with('vcontrollers', $vcontrollers);
    }

    public function showFiles() {
        $vrc = File::where('type', 0)->orderBy('name', 'ASC')->get();
        $vstars = File::where('type', 1)->orderBy('name', 'ASC')->get();
        $veram = File::where('type', 2)->orderBy('name', 'ASC')->get();
        $vatis = File::where('type', 3)->orderBy('name', 'ASC')->get();
        $sop = File::where('type', 4)->orderBy('name', 'ASC')->get();
        $loa = File::where('type', 5)->orderBy('name', 'ASC')->get();
        $staff = File::where('type', 6)->orderBy('name', 'ASC')->get();

        return view('dashboard.controllers.files')->with('vrc', $vrc)->with('vstars', $vstars)->with('veram', $veram)->with('vatis', $vatis)->with('sop', $sop)->with('loa', $loa)->with('staff', $staff);
    }

    public function downloadFile($id) {
        $file = File::find($id);
        $file_path = $file->path;

        return Response::download($file_path);
    }

    public function showTickets() {
        return view('dashboard.controllers.tickets');
    }

    public function showSuggestions() {
        return view('dashboard.controllers.suggestions');
    }

    public function showATCast() {
        return view('dashboard.controllers.atcast');
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
        return view('dashboard.controllers.stats')->with('all_stats', $all_stats)->with('year', $year)
                                                  ->with('month', $month)->with('stats', $stats)
                                                  ->with('home', $home)->with('visit', $visit);
    }

    public function showCalendarEvent($id) {
        $calendar = Calendar::find($id);

        return view('dashboard.controllers.calendar')->with('calendar', $calendar);
    }

    public function showFeedbackDetails($id) {
        $feedback = Feedback::find($id);
        return view('dashboard.controllers.feedback')->with('feedback', $feedback);
    }

    public function showEvents() {
        if(Auth::user()->can('events')) {
            $events = Event::where('status', 0)->orWhere('status', 1)->orderBy('date', 'DSC')->get();
        } else {
            $events = Event::where('status', 1)->get();
        }
        return view('dashboard.controllers.events.index')->with('events', $events);
    }

    public function viewEvent($id) {
        $event = Event::find($id);
        $positions = EventPosition::where('event_id', $event->id)->orderBy('created_at', 'ASC')->get();
        if(Auth::user()->can('events')) {
            $registrations = EventRegistration::where('event_id', $event->id)->where('status', 0)->orderBy('created_at', 'ASC')->get();
            $presets = PositionPreset::get()->pluck('name', 'id');
            $controllers = User::orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        } else {
            $presets = null;
            $registrations = null;
            $controllers = null;
        }

        $your_registration1 = EventRegistration::where('event_id', $event->id)->where('controller_id', Auth::id())->where('choice_number', 1)->first();
        $your_registration2 = EventRegistration::where('event_id', $event->id)->where('controller_id', Auth::id())->where('choice_number', 2)->first();
        $your_registration3 = EventRegistration::where('event_id', $event->id)->where('controller_id', Auth::id())->where('choice_number', 3)->first();

        return view('dashboard.controllers.events.view')->with('event', $event)->with('positions', $positions)->with('registrations', $registrations)->with('presets', $presets)->with('controllers', $controllers)
                                                        ->with('your_registration1', $your_registration1)->with('your_registration2', $your_registration2)->with('your_registration3', $your_registration3);
    }

    public function signupForEvent(Request $request) {
        $id = $request->event_id;
        if($request->num1 != null) {
            if($request->yr1 != null) {
                $reg = EventRegistration::find($request->yr1);
                $reg->event_id = $id;
                $reg->controller_id = Auth::id();
                $reg->position_id = $request->num1;
                $reg->start_time = $request->start_time1;
                $reg->end_time = $request->end_time1;
                $reg->status = 0;
                $reg->choice_number = 1;
                $reg->save();
            } else {
                $reg = new EventRegistration;
                $reg->event_id = $id;
                $reg->controller_id = Auth::id();
                $reg->position_id = $request->num1;
                $reg->start_time = $request->start_time1;
                $reg->end_time = $request->end_time1;
                $reg->status = 0;
                $reg->choice_number = 1;
                $reg->save();
            }
        }

        if($request->num2 != null) {
            if($request->yr2 != null) {
                $reg = EventRegistration::find($request->yr2);
                $reg->event_id = $id;
                $reg->controller_id = Auth::id();
                $reg->position_id = $request->num2;
                $reg->start_time = $request->start_time2;
                $reg->end_time = $request->end_time2;
                $reg->status = 0;
                $reg->choice_number = 2;
                $reg->save();
            } else {
                $reg = new EventRegistration;
                $reg->event_id = $id;
                $reg->controller_id = Auth::id();
                $reg->position_id = $request->num2;
                $reg->start_time = $request->start_time2;
                $reg->end_time = $request->end_time2;
                $reg->status = 0;
                $reg->choice_number = 2;
                $reg->save();
            }
        }

        if($request->num3 != null) {
            if($request->yr3 != null) {
                $reg = EventRegistration::find($request->yr3);
                $reg->event_id = $id;
                $reg->controller_id = Auth::id();
                $reg->position_id = $request->num3;
                $reg->start_time = $request->start_time3;
                $reg->end_time = $request->end_time3;
                $reg->status = 0;
                $reg->choice_number = 3;
                $reg->save();
            } else {
                $reg = new EventRegistration;
                $reg->event_id = $id;
                $reg->controller_id = Auth::id();
                $reg->position_id = $request->num3;
                $reg->start_time = $request->start_time3;
                $reg->end_time = $request->end_time3;
                $reg->status = 0;
                $reg->choice_number = 3;
                $reg->save();
            }
        }

        return redirect('/dashboard/controllers/events/view/'.$id)->with('success', 'Your event registration has been saved successfully.');
    }

    public function sceneryIndex() {
        $scenery = Scenery::orderBy('airport', 'ASC')->get();

        $fsx = $scenery->where('sim', 0);
        $xp = $scenery->where('sim', 1);
        $afcad = $scenery->where('sim', 2);

        return view('dashboard.controllers.scenery.index')->with('fsx', $fsx)->with('xp', $xp)->with('afcad', $afcad);
    }

    public function showScenery($id) {
        $scenery = Scenery::find($id);

        return view('dashboard.controllers.scenery.show')->with('scenery', $scenery);
    }

    public function searchAirport(Request $request) {
        $apt = $request->apt;
        return redirect('/dashboard/controllers/search-airport/search?apt='.$apt);
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

        return view('dashboard.controllers.airport')->with('apt_r', $apt_r)->with('metar', $metar)->with('taf', $taf)->with('visual_conditions', $visual_conditions)->with('pilots_a', $pilots_a)->with('pilots_d', $pilots_d)->with('charts', $charts);
    }

    public function optIn(Request $request) {
        if($request->opt != 1 || $request->privacy != 1) {
            return redirect()->back()->with('error', 'You have not been opted in. You must select both checkboxes if you would like to continue.');
        }

        $opt = new Opt;
        $opt->controller_id = Auth::id();
        $opt->option = 1;
        $opt->ip_address = $_SERVER['REMOTE_ADDR'];
        $opt->means = 'Electronic';
        $opt->save();

        $user = Auth::user();
        $user->opt = 1;
        $user->save();

        return redirect()->back()->with('success', 'You have been opted in successfully and will now receive broadcast emails from the vZTL ARTCC.');
    }

    public function optOut() {
        $opt = new Opt;
        $opt->controller_id = Auth::id();
        $opt->option = 0;
        $opt->ip_address = $_SERVER['REMOTE_ADDR'];
        $opt->means = 'Electronic';
        $opt->save();

        $user = Auth::user();
        $user->opt = 0;
        $user->save();

        return redirect()->back()->with('success', 'You have been opted out successfully and will no longer receive broadcast emails from the vZTL ARTCC.');
    }
}
