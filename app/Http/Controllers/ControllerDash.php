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
use App\Incident;
use App\LocalHero;
use App\LocalHeroChallenges;
use App\Opt;
use App\Overflight;
use App\OverflightUpdate;
use App\PositionPreset;
use App\Pyrite;
use App\Scenery;
use App\TrainingTicket;
use App\User;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mail;
use SimpleXMLElement;

class ControllerDash extends Controller {
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

        $now = Carbon::now();
        $last = Carbon::now()->subYear(1);

        $nmonth = $now->month;
        $year = substr($now->year, -2);
        $lyear = substr($last->year, -2);
        $month = $nmonth - '1';
        $pmonth = $month - '1';

        if ($month == 1) {
            $month_words = 'January';
        } elseif ($month == 2) {
            $month_words = 'Febuary';
        } elseif ($month == 3) {
            $month_words = 'March';
        } elseif ($month == 4) {
            $month_words = 'April';
        } elseif ($month == 5) {
            $month_words = 'May';
        } elseif ($month == 6) {
            $month_words = 'June';
        } elseif ($month == 7) {
            $month_words = 'July';
        } elseif ($month == 8) {
            $month_words = 'August';
        } elseif ($month == 9) {
            $month_words = 'September';
        } elseif ($month == 10) {
            $month_words = 'October';
        } elseif ($month == 11) {
            $month_words = 'November';
        } elseif ($month == 12 || $month == 0) {
            $month_words = 'December';
        }

        if ($pmonth == 1) {
            $pmonth_words = 'January';
        } elseif ($pmonth == 2) {
            $pmonth_words = 'Febuary';
        } elseif ($pmonth == 3) {
            $pmonth_words = 'March';
        } elseif ($pmonth == 4) {
            $pmonth_words = 'April';
        } elseif ($pmonth == 5) {
            $pmonth_words = 'May';
        } elseif ($pmonth == 6) {
            $pmonth_words = 'June';
        } elseif ($pmonth == 7) {
            $pmonth_words = 'July';
        } elseif ($pmonth == 8) {
            $pmonth_words = 'August';
        } elseif ($pmonth == 9) {
            $pmonth_words = 'September';
        } elseif ($pmonth == 10) {
            $pmonth_words = 'October';
        } elseif ($pmonth == 11 || $pmonth == -1) {
            $pmonth_words = 'November';
        } elseif ($pmonth == 12 || $pmonth == 0) {
            $pmonth_words = 'December';
        }

        if ($month < 1) {
            $year = substr($now->year, -2) - '1';
            if ($month == -1) {
                $month = 11;
            } elseif ($month == 0) {
                $month = 12;
            }
        } else {
            $year = substr($now->year, -2);
        }
        $winner = Bronze::where('month', $month)->where('year', $year)->first();
        $winner_local = LocalHero::where('month', $month)->where('year', $year)->first();

        if ($pmonth < 1) {
            $pyear = substr($now->year, -2) - '1';
            if ($pmonth == -1) {
                $pmonth = 11;
            } elseif ($pmonth == 0) {
                $pmonth = 12;
            }
        } else {
            $pyear = substr($now->year, -2);
        }
        $pwinner = Bronze::where('month', $pmonth)->where('year', $pyear)->first();
        $pwinner_local = LocalHero::where('month', $pmonth)->where('year', $pyear)->first();
        $pyrite = Pyrite::where('year', $lyear)->first();

        $default_challenge_description = "Control any field/any position other than ATL, CLT, and ZTL";
        $local_hero_challenge_this_month = LocalHeroChallenges::where('year', $year)->where('month', $month)->first();
        $local_hero_challenge_prev_month = LocalHeroChallenges::where('year', $pyear)->where('month', $pmonth)->first();
        $month_challenge_description = ($local_hero_challenge_this_month) ? $local_hero_challenge_this_month->title : $default_challenge_description;
        $pmonth_challenge_description = ($local_hero_challenge_prev_month) ? $local_hero_challenge_prev_month->title : $default_challenge_description;

        $controllers = ATC::get();

        $last_update = ControllerLogUpdate::orderBy('id', 'desc')->first();
        $controllers_update = substr($last_update->created_at, -8, 5);
        $events = Event::where('status', 1)->get()->filter(function ($e) use ($now) {
            return strtotime($e->date.' '.$e->start_time) > strtotime($now);
        })->sortBy(function ($e) {
            return strtotime($e->date);
        });
        
        $stats = ControllerLog::aggregateAllControllersByPosAndMonth(date('y'), date('n'));
        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $home = $homec->sortByDesc(function ($user) use ($stats) {
            return $stats[$user->id]->bronze_hrs;
        });
        $home = $home->take(5);

        $flights = Overflight::where('dep', '!=', '')->where('arr', '!=', '')->take(15)->get();
        $flights_update = substr(OverflightUpdate::first()->updated_at, -8, 5);

        return view('dashboard.dashboard')->with('calendar', $calendar)->with('news', $news)->with('announcement', $announcement)
                                          ->with('winner', $winner)->with('pwinner', $pwinner)->with('month_words', $month_words)->with('pmonth_words', $pmonth_words)
                                          ->with('controllers', $controllers)->with('controllers_update', $controllers_update)
                                          ->with('events', $events)
                                          ->with('pyrite', $pyrite)->with('lyear', $lyear)
                                          ->with('winner_local', $winner_local)->with('pwinner_local', $pwinner_local)
                                          ->with('month_challenge_description', $month_challenge_description)->with('pmonth_challenge_description', $pmonth_challenge_description)
                                          ->with('flights', $flights)->with('flights_update', $flights_update)->with('stats', $stats)->with('home', $home);
    }

    public function showProfile($year = null, $month = null) {
        if ($year == null) {
            $year = date('y');
        }

        if ($month == null) {
            $month = date('n');
        }

        $user_id = Auth::id();
        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
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

        // Begin new scheduling integration
        if (is_null(Auth::user()->ea_customer_id)) {
            $ea_users = DB::connection('ea_mysql')->select("id FROM ea_users WHERE notes = '" . Auth::user()->id . "' and id_roles = '3' LIMIT 1");
            if (is_array($ea_users)) {
                $u = reset($ea_users);
                $user = Auth::user();
                $user->ea_customer_id = $u->id;
                $user->save();
            }
        }
        $training_appointments = [];
        if (!is_null(Auth::user()->ea_customer_id)) {
            $ea_appointments = DB::connection('ea_mysql')->select("ea_appointments.start_datetime, ea_services.name, ea_providers.first_name, ea_providers.last_name FROM ea_appointments INNER JOIN ea_services ON ea_appointments.id_services = ea_services.id INNER JOIN ea_providers ON ea_appointments.provider_id = ea_providers.id WHERE ea_appointments.id_users_customer = '" . Auth::user()->ea_customer_id . "'");
            foreach ($ea_appointments as $a) {
                $training_appointments[] = [
                    'res_date' => Carbon::parse($a['start_datetime'])->format('m/d/Y'),
                    'res_time' => Carbon::parse($a['start_datetime'])->format('H:i'),
                    'service_description' => $a['name'],
                    'staff_name' => $a['first_name'] . ' ' . $a['last_name']
                ];
            }
        }
        /*
        $ea_data_stale = false;
        if(is_null(Auth::user()->ea_customer_id)) {
            $res = (new Client())->request('POST', Config::get('ea.base').'customers?fields=&with=',['headers' => [ 'Authorization' => 'Bearer ' . Config::get('ea.api_key') ]]);
            $status = $res->getStatusCode();
            if ($status != 200) {
                $ea_data_stale = true;
                $res_body = json_decode($res->getBody());
            } elseif ($res_body != '[]') {
                $user = Auth::user();
                $user->ea_customer_id = $res_body->id;
                $user->save();
            }
        }
        $res = (new Client())->request('POST', Config::get('ea.endpoint').'appointments?fields=&with=',['headers' => [ 'Authorization' => 'Bearer ' . Config::get('ea.api_key') ]]);
        $status = $res->getStatusCode();
        $ea_appointments = [];
        if ($status != 200) {
            $ea_data_stale = true;
            $res_body = json_decode($res->getBody());
        } elseif ($res_body != '[]') {
            foreach($res_body as $a) {
                $ea_appointment[] = [
                    'res_date'=>Carbon::parse($a->start)->format('m/d/Y'),
                    'res_time'=>Carbon::parse($a->start)->format('H:i'),
                    'service_description'=>EA_Service::getServiceDescription($a->serviceId),
                    'staff_name'=>EA_Provider::getProviderName($a->providerId)
                ];
            }
        }
*/
        return view('dashboard.controllers.profile')->with('personal_stats', $personal_stats)->with('feedback', $feedback)->with('tickets', $tickets)->with('last_training', $last_training)->with('last_training_given', $last_training_given)->with('ea_appointments', $training_appointments);
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
                                 ->with('month', $month)->with('stats', $stats)
                                 ->with('home', $home)->with('visit', $visit)->with('agreevisit', $agreevisit);
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
            $events = Event::where('status', 0)->orWhere('status', 1)->get()->sortByDesc(function ($e) {
                return strtotime($e->date);
            });
        } else {
            $events = Event::where('status', 1)->get()->sortByDesc(function ($e) {
                return strtotime($e->date);
            });
        }
        foreach ($events as $e) {
            $e->banner_path = $e->displayBannerPath();
        }
        return view('dashboard.controllers.events.index')->with('events', $events);
    }

    public function viewEvent($id) {
        $event = Event::find($id);
        $positions = EventPosition::where('event_id', $event->id)->orderBy('created_at', 'ASC')->get();
        if (Auth::user()->isAbleTo('events')||Auth::user()->hasRole('events-team')) {
            $registrations = EventRegistration::where('event_id', $event->id)->where('status', 0)->orderBy('created_at', 'ASC')->get();
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
            $reg->status = 0;
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
}
