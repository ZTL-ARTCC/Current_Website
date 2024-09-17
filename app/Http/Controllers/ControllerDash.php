<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\ATC;
use App\Bronze;
use App\Calendar;
use App\ControllerLog;
use App\Event;
use App\EventPosition;
use App\EventRegistration;
use App\Feedback;
use App\File;
use App\Incident;
use App\LiveEvent;
use App\LocalHero;
use App\LocalHeroChallenges;
use App\Opt;
use App\Overflight;
use App\PositionPreset;
use App\Pyrite;
use App\Scenery;
use App\TrainingTicket;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mail;
use SimpleXMLElement;

class ControllerDash extends Controller {
    protected static $SHOW_BOOKINGS_AFTER_APPT = 6; // Show bookings for X hours after appt start time

    public function dash() {
        $now = Carbon::now();

        $calendar = Calendar::where('type', '1')->where('visible', '1')->get()->filter(function ($news) use ($now) {
            return strtotime($news->date.' '.$news->time) > strtotime($now);
        })->sortBy(function ($news) {
            return strtotime($news->date.' '.$news->time);
        });
        $news = Calendar::where('type', '2')->where('visible', '1')->get()->filter(function ($news) use ($now) {
            return strtotime($news->date.' '.$news->time) < strtotime($now);
        })->sortByDesc(function ($news) {
            return strtotime($news->date.' '.$news->time);
        });
        $announcement = Announcement::find(1);

        $last_month = Carbon::now()->subMonth(1);
        $prev_month = Carbon::now()->subMonth(2);
        $last_year = Carbon::now()->subYear(1);

        $winner_bronze = Bronze::where('month', $last_month->format('n'))->where('year', $last_month->format('y'))->first();
        $winner_local = LocalHero::where('month', $last_month->format('n'))->where('year', $last_month->format('y'))->first();
        $prev_winner_bronze = Bronze::where('month', $prev_month->format('n'))->where('year', $prev_month->format('y'))->first();
        $prev_winner_local = LocalHero::where('month', $prev_month->format('n'))->where('year', $prev_month->format('y'))->first();
        $pyrite = Pyrite::where('year', $last_year->format('y'))->first();

        $default_challenge_description = "Control any field/any position other than ATL, CLT, and ZTL";
        $local_hero_challenge_this_month = LocalHeroChallenges::where('year', $last_month->format('y'))->where('month', $prev_month->format('n'))->first();
        $local_hero_challenge_prev_month = LocalHeroChallenges::where('year', $prev_month->format('y'))->where('month', $prev_month->format('n'))->first();
        $month_challenge_description = ($local_hero_challenge_this_month) ? $local_hero_challenge_this_month->title : $default_challenge_description;
        $pmonth_challenge_description = ($local_hero_challenge_prev_month) ? $local_hero_challenge_prev_month->title : $default_challenge_description;

        $controllers = ATC::get();

        $events = Event::where('status', 1)->get()->filter(function ($e) use ($now) {
            return strtotime($e->date.' '.$e->start_time) > strtotime($now);
        })->sortBy(function ($e) {
            return strtotime($e->date);
        });
        foreach ($events as $e) {
            $e->banner_path = $e->displayBannerPath();
        }
        
        $stats = ControllerLog::aggregateAllControllersByPosAndMonth(date('y'), date('n'));
        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $home = $homec->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->bronze_hrs;
        });
        $home = $home->take(5);

        $flights = Overflight::where('dep', '!=', '')->where('arr', '!=', '')->take(15)->get();

        $training_metrics = $top_trainers = [];
        $training_stats = TrainingDash::generateTrainingStats($now->format('Y'), $now->format('m'), 'stats');
        $training_metrics[] = (object)['title' => 'S1', 'metric' => $training_stats['sessionsByType']['S1']];
        $training_metrics[] = (object)['title' => 'S2', 'metric' => $training_stats['sessionsByType']['S2']];
        $training_metrics[] = (object)['title' => 'S3', 'metric' => $training_stats['sessionsByType']['S3']];
        $training_metrics[] = (object)['title' => 'C1', 'metric' => $training_stats['sessionsByType']['C1']];
        $total_sessions = $training_stats['sessionsByType']['S1'] + $training_stats['sessionsByType']['S2'] + $training_stats['sessionsByType']['S3'] + $training_stats['sessionsByType']['C1'];
        $training_metrics[] = (object)['title' => 'Total', 'metric' => $total_sessions];
        $trainer_by_total = $trainer_by_cid = [];
        foreach($training_stats['trainerSessions'] as $t) {
            $trainer_by_total[$t['cid']] = $t['total'];
            $trainer_by_cid[$t['cid']] = $t['name'];
        }
        arsort($trainer_by_total);
        foreach($trainer_by_total as $trainer_cid => $tt) {
            if($tt == 0) {
                break;
            }
            $top_trainers[] = (object)['name' => $trainer_by_cid[$trainer_cid], 'sessions_given' => $tt];
            if(count($top_trainers) == 3) {
                break;
            }
        }

        $live_event = LiveEvent::find(1);
        $live_event_title = (strlen($live_event) == 0) ? 'Live Event' : $live_event->event_title;

        return view('dashboard.dashboard')->with('calendar', $calendar)->with('news', $news)->with('announcement', $announcement)
                                          ->with('winner', $winner_bronze)->with('pwinner', $prev_winner_bronze)->with('month_words', $last_month->format('F'))->with('pmonth_words', $prev_month->format('F'))
                                          ->with('controllers', $controllers)
                                          ->with('events', $events)
                                          ->with('pyrite', $pyrite)->with('lyear', $last_year->format('Y'))
                                          ->with('winner_local', $winner_local)->with('pwinner_local', $prev_winner_local)
                                          ->with('month_challenge_description', $month_challenge_description)->with('pmonth_challenge_description', $pmonth_challenge_description)
                                          ->with('training_metrics', $training_metrics)->with('top_trainers', $top_trainers)
                                          ->with('flights', $flights)->with('stats', $stats)->with('home', $home)
                                          ->with('liveEventTitle', $live_event_title);
    }

    public function showProfile($year = null, $month = null) {
        if ($year == null) {
            $year = date('y');
        }

        if ($month == null) {
            $month = date('n');
        }

        $user_id = Auth::id();
        $stats = ControllerLog::aggregateAllControllersByPosAndQuarter($year, $month);
        $feedback = Feedback::where('controller_id', $user_id)->where('status', 1)->orderBy('updated_at', 'ASC')->paginate(10);
        $personal_stats = $stats[$user_id];
        $tickets_sort = TrainingTicket::where('controller_id', Auth::id())->get()->sortByDesc(function ($t) {
            return strtotime($t->date.' '.$t->start_time);
        })->pluck('id');
        if ($tickets_sort->count() != 0) {
            $tickets_order = implode(',', array_fill(0, count($tickets_sort), '?'));
            $tickets = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->paginate(10);
            $last_training = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->first();
        } else {
            $tickets = null;
            $last_training = null;
        }

        if (Auth::user()->isAbleTo('train')) {
            $tickets_sort_t = TrainingTicket::where('trainer_id', Auth::id())->get()->sortByDesc(function ($t) {
                return strtotime($t->date.' '.$t->start_time);
            })->pluck('id');
            if ($tickets_sort_t->count() != 0) {
                $tickets_order_t = implode(',', array_fill(0, count($tickets_sort_t), '?'));
                $last_training_given = TrainingTicket::whereIn('id', $tickets_sort_t)->orderByRaw("field(id,{$tickets_order_t})", $tickets_sort_t)->first();
            } else {
                $last_training_given = null;
            }
        } else {
            $last_training_given = null;
        }

        if (is_null(Auth::user()->ea_customer_id)) {
            try {
                $ea_users = DB::connection('ea_mysql')->table('ea_users')->select('id')->where(function ($query) {
                    $query->where('email', Auth::user()->email)
                          ->orWhere('custom_field_1', Auth::user()->id);
                })->where('id_roles', '3')->limit(1)->get();
                foreach ($ea_users as $u) {
                    $user = Auth::user();
                    $user->ea_customer_id = $u->id;
                    $user->save();
                }
            } catch (\Illuminate\Database\QueryException $e) {
            }
        }
        $ea_appointments = [];
        if (!is_null(Auth::user()->ea_customer_id)) {
            try {
                $ea_appointments = DB::connection('ea_mysql')
                    ->table('ea_appointments')
                    ->join('ea_services', 'ea_appointments.id_services', '=', 'ea_services.id')
                    ->join('ea_users', 'ea_appointments.id_users_provider', '=', 'ea_users.id')
                    ->select('ea_appointments.start_datetime AS start_datetime', 'ea_appointments.hash AS link_token', 'ea_services.name AS service_description', 'ea_users.first_name AS staff_first_name', 'ea_users.last_name AS staff_last_name')
                    ->where('ea_appointments.id_users_customer', Auth::user()->ea_customer_id)
                    ->where('ea_appointments.start_datetime', '>=', Carbon::now('America/New_York')->subHours(self::$SHOW_BOOKINGS_AFTER_APPT)->format('Y-m-d H:i:s'))
                    ->orderBy('ea_appointments.start_datetime', 'ASC')->get();
                foreach ($ea_appointments as &$ea_appointment) {
                    $ea_appointment->res_date = Carbon::parse($ea_appointment->start_datetime)->format('m/d/Y');
                    $ea_appointment->res_time = Carbon::parse($ea_appointment->start_datetime)->format('H:i');
                    $ea_appointment->staff_name = $ea_appointment->staff_first_name . ' ' . $ea_appointment->staff_last_name;
                }
            } catch (\Illuminate\Database\QueryException $e) {
            }
        }

        return view('dashboard.controllers.profile')->with('personal_stats', $personal_stats)->with('feedback', $feedback)->with('tickets', $tickets)->with('last_training', $last_training)->with('last_training_given', $last_training_given)->with('ea_appointments', $ea_appointments);
    }

    public function showTicket($id) {
        $ticket = TrainingTicket::find($id);
        if (Auth::id() == $ticket->controller_id) {
            return view('dashboard.controllers.ticket')->with('ticket', $ticket);
        } else {
            return redirect()->back()->with('error', 'You can only view your own tickets. If you are a trainer trying to view a ticket, please do that from the training section.');
        }
    }

    public function showRoster() {
        $hcontrollers = User::where('status', '!=', 2)->where('visitor', '0')->where('status', '1')->orWhere('status', '0')->orderBy('lname', 'ASC')->get();
        $vcontrollers = User::where('status', '!=', 2)->where('visitor', '1')->where('status', '1')->orderBy('lname', 'ASC')->get();
 
        return view('dashboard.controllers.roster')->with('hcontrollers', $hcontrollers)->with('vcontrollers', $vcontrollers);
    }

    public function showFiles() {
        $vatis = File::where('type', 3)->orderBy('disp_order', 'ASC')->get();
        for ($x=0;$x<count($vatis);$x++) {
            $file = File::find($vatis[$x]['id']);
            $file->disp_order = $x;
            $file->timestamps = false;
            $file->save();
        }
        $sop = File::where('type', 4)->orderBy('disp_order', 'ASC')->get();
        for ($x=0;$x<count($sop);$x++) {
            $file = File::find($sop[$x]['id']);
            $file->disp_order = $x;
            $file->timestamps = false;
            $file->save();
        }
        $loa = File::where('type', 5)->orderBy('disp_order', 'ASC')->get();
        for ($x=0;$x<count($loa);$x++) {
            $file = File::find($loa[$x]['id']);
            $file->disp_order = $x;
            $file->timestamps = false;
            $file->save();
        }
        $staff = File::where('type', 6)->orderBy('disp_order', 'ASC')->get();
        for ($x=0;$x<count($staff);$x++) {
            //File::where('id',$staff[$x]['id'])->update(['disp_order' => $x]);
            $file = File::find($staff[$x]['id']);
            $file->disp_order = $x;
            $file->timestamps = false;
            $file->save();
        }
        $training = File::where('type', 7)->orderBy('disp_order', 'ASC')->get();
        for ($x=0;$x<count($training);$x++) {
            $file = File::find($training[$x]['id']);
            $file->disp_order = $x;
            $file->timestamps = false;
            $file->save();
        }
        
        return view('dashboard.controllers.files')->with('vatis', $vatis)->with('sop', $sop)->with('loa', $loa)->with('staff', $staff)->with('training', $training);
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
        if ($year == null) {
            $year = date('y');
        }

        if ($month == null) {
            $month = date('n');
        }

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $qtr_stats = ControllerLog::aggregateAllControllersByPosAndQuarter($year, $month);
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

        return view('dashboard.controllers.stats')->with('all_stats', $all_stats)->with('year', $year)
                                 ->with('month', $month)->with('stats', $stats)->with('qtr_stats', $qtr_stats)
                                 ->with('home', $home)->with('visiting', $visit)->with('agreevisit', $agreevisit);
    }

    public function showCalendarEvent($id) {
        $calendar = Calendar::find($id);

        return view('dashboard.controllers.calendar')->with('calendar', $calendar);
    }

    public function showFeedbackDetails($id) {
        $feedback = Feedback::find($id);
        if ($feedback->controller_id != Auth::id()) {
            return redirect('dashboard/controllers/profile')->with('error', 'You\'re not allowed to see this!');
        }
            
        return view('dashboard.controllers.feedback')->with('feedback', $feedback);
    }

    public function showEvents() {
        if (Auth::user()->isAbleTo('events')||Auth::user()->hasRole('events-team')) {
            $events = Event::all()->sortByDesc('date_stamp')->paginate(10);
        } else {
            $events = Event::where('status', 1)->get()->sortByDesc('date_stamp');
        }
        foreach ($events as $e) {
            $e->banner_path = $e->displayBannerPath();
        }
        return view('dashboard.controllers.events.index')->with('events', $events);
    }

    public function viewEvent($id) {
        $event = Event::find($id);
        $event->banner_path = $event->displayBannerPath();
        $positions = EventPosition::where('event_id', $event->id)->orderBy('created_at', 'ASC')->get();
        if (Auth::user()->isAbleTo('events')||Auth::user()->hasRole('events-team')) {
            $registrations = EventRegistration::where('event_id', $event->id)->where('status', EventRegistration::STATUSES['UNASSIGNED'])->orderBy('created_at', 'ASC')->get();
            $presets = PositionPreset::get()->pluck('name', 'id');
            $controllers = User::orderBy('lname', 'ASC')->get()->pluck('backwards_name_rating', 'id');
        } else {
            $presets = null;
            $registrations = null;
            $controllers = null;
        }

        $your_registration1 = EventRegistration::where('event_id', $event->id)->where('controller_id', Auth::id())->where('choice_number', 1)->first();
        $your_registration2 = EventRegistration::where('event_id', $event->id)->where('controller_id', Auth::id())->where('choice_number', 2)->first();
        $your_registration3 = EventRegistration::where('event_id', $event->id)->where('controller_id', Auth::id())->where('choice_number', 3)->first();

        $user = Auth::user();
        $timezone = $user->timezone;

        $local_start_time = timeToLocal($event->start_time, $timezone);
        $local_end_time = timeToLocal($event->end_time, $timezone);

        return view('dashboard.controllers.events.view')->with('event', $event)->with('positions', $positions)->with('registrations', $registrations)->with('presets', $presets)->with('controllers', $controllers)
                                                        ->with('your_registration1', $your_registration1)->with('your_registration2', $your_registration2)->with('your_registration3', $your_registration3)->with('timezone', $timezone)
                                                        ->with('local_start_time', $local_start_time)->with('local_end_time', $local_end_time);
    }

    public function signupForEvent(Request $request) {
        // Validate start_time and end_time
        // Also convert them to UTC
        // https://regex101.com/r/4LGhop/1
        $valid_time_expr = '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/';
        $id = $request->event_id;

        if (($request->start_time1 == "" || $request->start_time1 == " ") && ($request->end_time1 == "" || $request->end_time1 == " ")) {
            $e = Event::find($id);
            $request->start_time1 = $e->start_time;
            $request->end_time1 = $e->end_time;
            $request->timezone == "0";
        }

        if (!preg_match($valid_time_expr, $request->start_time1)) {
            return redirect()->back()->with('error', 'Invalid signup start time. Must be in the format HH:MM, and only contain numbers and `:`.');
        }
        if (!preg_match($valid_time_expr, $request->end_time1)) {
            return redirect()->back()->with('error', 'Invalid signup end time. Must be in the format HH:MM, and only contain numbers and `:`.');
        }

        if ($request->timezone == '1') { // Local: 1
            $request->start_time1 = timeFromLocal($request->start_time1, Auth::user()->timezone);
            $request->end_time1 = timeFromLocal($request->end_time1, Auth::user()->timezone);
        }

        if ($request->num1 == null && $request->yr1 == null) {
            return redirect()->back()->with('error', 'You need to select a position to sign up for.');
        }

        if ($request->num1 != null) {
            $reg = new EventRegistration;
            $reg->event_id = $id;
            $reg->controller_id = Auth::id();
            $reg->position_id = $request->num1;
            $reg->start_time = $request->start_time1;
            $reg->end_time = $request->end_time1;
            $reg->status = EventRegistration::STATUSES['UNASSIGNED'];
            $reg->choice_number = 1;
            $reg->remarks = $request->remarks;
            $reg->save();
        } else {
            $reg = EventRegistration::find($request->yr1);
            if ($reg) {
                $reg->delete();
            }
        }

        return redirect('/dashboard/controllers/events/view/'.$id)->with('success', 'Your event registration has been saved successfully.');
    }

    public function unsignupForEvent($id) {
        // Get the position request to be deleted
        $request = EventRegistration::find($id);

        // Delete the request
        $request->delete();

        // Go back
        return redirect()->back()->with('success', 'Your registration has been removed successfully.');
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

        return view('dashboard.controllers.scenery.index')->with('fsx', $fsx)->with('xp', $xp)->with('afcad', $afcad);
    }

    public function searchScenery(Request $request) {
        return redirect('/dashboard/controllers/scenery?search='.$request->search);
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
        if (strlen($apt) == 3) {
            $apt_s = 'k'.strtolower($apt);
        } elseif (strlen($apt) == 4) {
            $apt_s = strtolower($apt);
        } else {
            return redirect()->back()->with('error', 'You either did not search for an airport or the airport ID is too long.');
        }

        $apt_r = strtoupper($apt_s);

        $client = new Client(['http_errors' => false]);
        $response_metar = $client->request('GET', 'https://aviationweather.gov/cgi-bin/data/dataserver.php?dataSource=metars&requestType=retrieve&format=xml&hoursBeforeNow=2&mostRecentForEachStation=true&stationString='.$apt_s);
        $response_taf = $client->request('GET', 'https://aviationweather.gov/cgi-bin/data/dataserver.php?dataSource=tafs&requestType=retrieve&format=xml&hoursBeforeNow=2&mostRecentForEachStation=true&stationString='.$apt_s);

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

        return view('dashboard.controllers.airport')->with('apt_r', $apt_r)->with('metar', $metar)->with('taf', $taf)->with('visual_conditions', $visual_conditions)->with('pilots_a', $pilots_a)->with('pilots_d', $pilots_d)
                                                    ->with('charts', $charts)->with('min', $min)->with('hot', $hot)->with('lah', $lah)->with('apd', $apd)->with('iap', $iap)->with('dp', $dp)->with('star', $star)->with('cvfp', $cvfp);
    }

    public function optIn(Request $request) {
        if ($request->opt != 1 || $request->privacy != 1) {
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

    public function incidentReport() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        return view('dashboard.controllers.incident_report')->with('controllers', $controllers);
    }

    public function submitIncidentReport(Request $request) {
        $validator = $request->validate([
            'controller_id' => 'required',
            'controller_callsign' => 'required',
            'reporter_callsign' => 'required',
            'date' => 'required',
            'time' => 'required',
            'description' => 'required'
        ]);

        $incident = new Incident;
        $incident->controller_id = $request->controller_id;
        $incident->controller_callsign = $request->controller_callsign;
        $incident->reporter_id = Auth::id();
        $incident->reporter_callsign = $request->reporter_callsign;
        $incident->aircraft_callsign = $request->aircraft_callsign;
        $incident->time = $request->time;
        $incident->date = $request->date;
        $incident->description = $request->description;
        $incident->status = 0;
        $incident->save();

        return redirect('/dashboard')->with('success', 'Your report has been submitted successfully.');
    }

    public function reportBug(Request $request) {
        $validator = $request->validate([
            'desc' => 'required'
        ]);
        $reporter = User::find(Auth::id());
        $url = $request->url;
        $error = $request->error;
        $desc = $request->desc;

        Mail::send('emails.bug', ['reporter' => $reporter, 'url' => $url, 'error' => $error, 'desc' => $desc], function ($m) use ($reporter) {
            $m->from('bugs@notams.ztlartcc.org', 'vZTL ARTCC Bugs')->replyTo($reporter->email, $reporter->full_name);
            $m->subject('New Bug Report');
            $m->to('wm@ztlartcc.org');
        });

        return redirect()->back()->with('success', 'Your bug has been reported successfully.');
    }

    public function updateInfo(Request $request) {
        $user = Auth::user();
        $user->ts3 = $request->ts3;
        $user->timezone = $request->timezone;
        $user->save();
        return redirect()->back()->with('success', 'Your profile has been updated successfully.');
    }

    public function updateDiscordRoles(Request $request) {
        $user = Auth::user();
        $user_id = $user->discord;

        if (!$user_id) {
            return redirect()->back()->with('error', 'You must have a Discord UID set in order to update your roles.');
        }

        $response = Http::get('http://bot.ztlartcc.org:3000/assignRoles', [
            'userId' => $user_id,
        ]);

        if ($response->notFound()) {
            return redirect()->back()->with('error', 'You have not been found in the Discord server. Please make sure you are in the server and your id is correct.');
        } elseif (!$response->successful()) {
            return redirect()->back()->with('error', 'An error occurred while updating your roles. Please try again later.');
        }

        return redirect()->back()->with('success', 'Your roles have been updated successfully.');
    }

    public function showLiveEventInfo() {
        $live_event = LiveEvent::find(1);
        return view('dashboard.controllers.live_event_info')->with('liveEventInfo', $live_event);
    }
}
