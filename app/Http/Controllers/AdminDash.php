<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Announcement;
use App\Audit;
use App\Bronze;
use App\Calendar;
use App\ControllerLog;
use App\Event;
use App\EventPosition;
use App\EventRegistration;
use App\FeatureToggle;
use App\Feedback;
use App\File;
use App\Incident;
use App\LocalHero;
use App\LocalHeroChallenges;
use App\Metar;
use App\PositionPreset;
use App\PresetPosition;
use App\Pyrite;
use App\Scenery;
use App\ScheduleMonitorTasks;
use App\SoloCert;
use App\User;
use App\Visitor;
use App\VisitRej;
use Artisan;
use Auth;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Mail;

class AdminDash extends Controller {
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
        $scenery->airport = $request->input('apt');
        $scenery->developer = $request->input('dev');
        $scenery->sim = $request->input('sim');
        $scenery->link = $request->input('url');
        $scenery->price = $request->input('price');
        $scenery->currency = $request->input('currency');
        $scenery->image1 = $request->input('image1');
        $scenery->image2 = $request->input('image2');
        $scenery->image3 = $request->input('image3');
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
        $scenery->airport = $request->input('apt');
        $scenery->developer = $request->input('dev');
        $scenery->sim = $request->input('sim');
        $scenery->link = $request->input('url');
        $scenery->image1 = $request->input('image1');
        $scenery->image2 = $request->input('image2');
        $scenery->image3 = $request->input('image3');
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
            'ICAO' => 'required|unique:airports,ltr_4'
        ]);

        $a = new Airport;
        $a->name = $request->input('name');
        $a->ltr_3 = $request->input('FAA');
        $a->ltr_4 = $request->input('ICAO');
        $a->save();

        $metar = new Metar;
        $metar->icao = $request->input('ICAO');
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

        $mtr = $hcontrollers->filter(function ($user) {
            return $user->hasRole('mtr');
        });
        $ins = $hcontrollers->filter(function ($user) {
            return $user->hasRole('ins');
        });

        return view('dashboard.admin.roster.index')->with('hcontrollers', $hcontrollers)->with('vcontrollers', $vcontrollers)->with('mtr', $mtr)->with('ins', $ins);
    }

    public function showRosterPurge($year = null, $month = null) {
        if ($year == null) {
            $year = date('y');
        }

        if ($month == null) {
            $month = date('n');
        }

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $homec = User::where('visitor', 0)->where('status', 1)->orderBy('lname', 'ASC')->get();
        $visitc = User::where('visitor', 1)->where('status', 1)->where('visitor_from', '!=', 'ZHU')->where('visitor_from', '!=', 'ZJX')->orderBy('lname', 'ASC')->get();
        $trainc = User::orderBy('lname', 'ASC')->get()->filter(function ($user) {
            return $user->hasRole('mtr') || $user->hasRole('ins');
        });

        if ($month == 1) {
            $last_year = $year - 1;
        } else {
            $last_year = $year;
        }

        if ($month == 1) {
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
        $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
        $exp_date = null;
        if (!is_null($solo)) {
            $exp_date = Carbon::createFromFormat('Y-m-d', $solo->expiration);
            $user->solo_exp = $exp_date->format('m/d/Y');
            if ($exp_date->isPast()) {
                $user->solo_exp .= ' Expired!';
            }
        } else {
            $user->solo_exp = '';
        }
        if ($user->del == 2) {
            $user->del = 1;
            $user->clt_del = 1;
            $user->atl_del = 1;
        }
        if ($user->gnd == 2) {
            $user->gnd = 1;
            $user->clt_gnd = 1;
            $user->atl_gnd = 1;
        }
        if ($user->twr == 2) {
            $user->twr = 1;
            $user->clt_twr = 1;
            $user->atl_twr = 1;
        }
        if ($user->app == 2) {
            $user->app = 1;
            $user->clt_app = 1;
            $user->atl_app = 1;
        }

        $user_events = [];
        $event_stats = new \stdClass();
        $event_stats->events_total = $event_stats->events_total_12mo = $event_stats->hours_total = $event_stats->hours_total_12mo
            = $event_stats->no_shows = $event_stats->no_shows_12mo = 0;
        $inclusive_hours = 2; //How many hours before/after event should we search for in the controller log?
        $event_registrations = EventRegistration::where('controller_id', $user->id)->get();
        foreach ($event_registrations as $event_registration) {
            $event = Event::find($event_registration->event_id);
            $position = EventPosition::find($event_registration->position_id);
            $user_event = new \stdClass();
            $user_event->id = $event->id;
            $user_event->event_date = $event->date;
            $user_event->event_name = $event->name;
            $user_event->position_assigned = $position->name;
            $user_event->time_logged = 0;
            $user_event->no_show = $event_registration->no_show;
            $user_event->connection = [];
            $event_stats->events_total++;
            $event_stats->no_shows += ($event_registration->no_show == 1) ? 1 : 0;
            $event_start = Carbon::createFromFormat('m/d/Y H:i:s', $event->date . ' ' . $event->start_time . ':00')->subHours($inclusive_hours);
            $next_day = 0;
            if ($event->start_time > $event->end_time) { // This is incapable of handling multi-day events due to database limitations
                $next_day = 1;
            }
            $event_end = Carbon::createFromFormat('m/d/Y H:i:s', $event->date . ' ' . $event->end_time . ':00')->addDays($next_day)->addHours($inclusive_hours);
            $connections = ControllerLog::where('cid', $user->id)
                ->where(\DB::raw("STR_TO_DATE(date,'%m/%d/%Y')"), '>=', $event_start->format('Y-m-d'))
                ->where(\DB::raw("STR_TO_DATE(date,'%m/%d/%Y')"), '<=', $event_end->format('Y-m-d'))
                ->get();
            foreach ($connections as $connection) {
                $time_logoff = $connection->time_logon + $connection->duration;
                if (($event_start->timestamp > $time_logoff)||($event_end->timestamp < $connection->time_logon)) {
                    continue;
                }
                $event_connection = new \stdClass();
                $event_connection->callsign = $connection->position;
                $event_connection->start = Carbon::createFromTimestamp($connection->time_logon)->format('m/d H:i');
                $event_connection->end = Carbon::createFromTimestamp($time_logoff)->format('m/d H:i');
                $user_event->connection[] = $event_connection;
                $user_event->time_logged += $connection->duration / 60 / 60;
            }
            $user_event->time_logged = round($user_event->time_logged, 1);
            $event_stats->hours_total += $user_event->time_logged;
            if ($event_start > Carbon::now()->subYear(1)) {
                $event_stats->events_total_12mo++;
                $event_stats->hours_total_12mo += $user_event->time_logged;
                $event_stats->no_shows_12mo += ($event_registration->no_show == 1) ? 1 : 0;
            }
            $user_events[] = $user_event;
        }

        return view('dashboard.admin.roster.edit')->with('user', $user)->with('events', $user_events)->with('event_stats', $event_stats);
    }

    public function updateController(Request $request, $id) {
        $user = User::find($id);

        if (Auth::user()->isAbleTo('roster')) { // Update controller profile and account settings
            $user->initials = $request->input('initials');
            $user->max = $request->input('max');
          
            $attributes = ['canTrain', 'canEvents', 'api_exempt'];
            foreach ($attributes as $attribute) {
                $user[$attribute] = ($request->input($attribute) == 1) ? 1 : 0;
            }
            $user->status = $request->input('status');
            $user->visitor_from = $request->input('visitor_from');
            $user->save();

            $staff_roles = $user->facility_staff;
            foreach ($staff_roles as $staff_role) {
                if ($user->hasRole(strtolower($staff_role))) {
                    $user->removeRole(strtolower($staff_role));
                }
            }
            $staff_roles = $user->getMagicNumber('FACILITY_STAFF_POSITION_MAP');
            foreach ($staff_roles as $role_id => $staff_role) {
                if ($request->input('staff') == $role_id) {
                    $user->addRole($staff_role);
                }
            }

            if ($user->hasRole(['mtr', 'ins']) == true) {
                if ($user->hasRole('mtr')) {
                    $user->removeRole('mtr');
                    $user->save();
                } elseif ($user->hasRole('ins')) {
                    $user->removeRole('ins');
                    $user->save();
                }
            }

            if ($request->input('training') == 1) {
                $user->addRole('mtr');
                if ($user->train_pwr == null) {
                    $user->train_pwr = 1;
                    $user->monitor_pwr = 1;
                    $user->save();
                }
            } elseif ($request->input('training') == 2) {
                $user->addRole('ins');
                if ($user->train_pwr == null) {
                    $user->train_pwr = 6;
                    $user->monitor_pwr = 6;
                    $user->save();
                }
            }
        }
        if (Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train')) { // Update training certifications
            $positions = ['gnd','clt_del','clt_gnd','clt_twr','clt_app','atl_del','atl_gnd','atl_twr','atl_app','twr_solo_fields'];
            foreach ($positions as $position) {
                $user[$position] = ($request->input($position)) ? $request->input($position) : $user[$position];
            }
            $positions = ['twr','app','ctr'];
            $solo_facilities = [ // Facilities submitted to VATUSA for solo certs
                'twr' => 'GSO',
                'app' => 'GSO',
                'ctr' => 'ZTL'];
            foreach ($positions as $solo_id => $position) {
                if ($user[$position] == $user->getMagicNumber('SOLO_CERTIFICATION')) {
                    if ($request->input($position) != 0) {
                        $solo = SoloCert::where('cid', $user->id)->where('status', 0)->first();
                        if ($solo) {
                            $solo->status = 1;
                            $solo->save();
                        }
                        $user[$position] = $request->input($position);
                    } else {
                        $user[$position] = $user->getMagicNumber('SOLO_CERTIFICATION');
                    }
                } elseif ($request->input($position) == $user->getMagicNumber('SOLO_CERTIFICATION')) {
                    $user[$position] = $request->input($position);
                    $expire = Carbon::now()->addDays($user->getMagicNumber('SOLO_CERT_DURATION'))->format('Y-m-d');
                    $cert = new SoloCert;
                    $cert->cid = $id;
                    $cert->pos = $solo_id;
                    $cert->expiration = $expire;
                    $cert->status = 0;
                    $cert->save();
                    $solo_facility = $solo_facilities[$position] . '_' . strtoupper($position);
                    (new Client())->request('POST', Config::get('vatusa.base').'/v2/solo'.'?apikey='.Config::get('vatusa.api_key').'&cid='.$id.'&position='.$solo_facility.'&expDate='.$expire, ['http_errors' => false]);
                } else {
                    $user[$position] = ($request->input($position)) ? $request->input($position) : $user[$position];
                }
            }
            $user->twr_solo_fields = $request->input('twr_solo_fields');
            $user->save();
        }
        if (Auth::user()->isAbleTo('roster') || Auth::user()->hasRole('ec')) { // Update events team
            if ($user->hasRole(['aec','aec-ghost','events-team']) == true) {
                if ($user->hasRole('aec')) {
                    $user->removeRole('aec');
                } elseif ($user->hasRole('aec-ghost')) {
                    $user->removeRole('aec-ghost');
                } elseif ($user->hasRole('events-team')) {
                    $user->removeRole('events-team');
                }
            }

            if ($request->input('events_staff') == 1) {
                $user->addRole('aec');
            } elseif ($request->input('events_staff') == 2) {
                $user->addRole('aec-ghost');
            } elseif ($request->input('events_staff') == 3) {
                $user->addRole('events-team');
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
        $validator = $request->validate([
            'cid' => 'required'
        ]);

        $id = $request->cid;
        $visitrej = VisitRej::where('cid', $id)->first();

        if ($visitrej == null) {
            return redirect('/dashboard/controllers/roster')->with('error', 'Controller not found.');
        }
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

    public function acceptVisitRequest($id) {
        $visitor = Visitor::find($id);
        $visitor->updated_by = Auth::id();
        $visitor->status = 1;
        $visitor->save();

        Mail::send('emails.visit.accept', ['visitor' => $visitor], function ($message) use ($visitor) {
            $message->from('visitors@notams.ztlartcc.org', 'vZTL ARTCC Visiting Department')->subject('Visitor Request Accepted');
            $message->to($visitor->email)->cc('datm@ztlartcc.org');
        });

        $parts = explode(" ", $visitor->name);
        $fname = $parts[0];
        $lname = $parts[1];
        $initials = User::generateControllerInitials($fname, $lname);

        if (User::find($visitor->cid) !== null) {
            $user = User::find($visitor->cid);
        } else {
            $user = false;
        }

        return view('dashboard.admin.roster.new_vis')->with('visitor', $visitor)->with('initials', $initials)->with('fname', $fname)->with('lname', $lname)->with('user', $user);
    }
    
    public function manualAddVisitor(Request $request) {
        $validator = $request->validate([
            'cid' => 'required'
        ]);

        $client = new Client(['exceptions' => false]);
        $response = $client->request('GET', Config::get('vatusa.base').'/v2/user/'.$request->cid.'?apikey='.Config::get('vatusa.api_key'));
        $result = $response->getStatusCode();
        if ($result == '200') {
            $visitor = json_decode($response->getBody());
            $visitor = $visitor->data;
            $initials = User::generateControllerInitials($visitor->fname, $visitor->lname);
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

        Mail::send(['html' => 'emails.visit.reject'], ['visitor' => $visitor], function ($message) use ($visitor) {
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
        // See if a record already exists for this CID (a returning visitor)
        if (User::find($request->cid) !== null) {
            $user = User::find($request->cid);
        } else {
            $user = new User;
        }
        $user->id = $request->input('cid');
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');
        $user->initials = $request->input('initials');
        $user->rating_id = $request->input('rating_id');
        if ((User::find($request->input('cid')) !== null)&&($request->input('grant_previous') == '1')) {
            // Grant all previous certifications that controller held
        } else { // Otherwise, grant minor certifications based on GRP
            if ($request->input('rating_id') == 2) {
                $user->del = 1;
                $user->gnd = 1;
                $user->twr = 0;
                $user->app = 0;
                $user->ctr = 0;
            } elseif ($request->input('rating_id') == 3) {
                $user->del = 1;
                $user->gnd = 1;
                $user->twr = 1;
                $user->app = 0;
                $user->ctr = 0;
            } elseif ($request->input('rating_id') == 4 || $request->input('rating_id') == 5 || $request->input('rating_id') == 7 || $request->input('rating_id') == 8 || $request->input('rating_id') == 10) {
                $user->del = 1;
                $user->gnd = 1;
                $user->twr = 1;
                $user->app = 1;
                $user->ctr = 0;
            }
        }
        $user->visitor = '1';
        $user->visitor_from = $request->input('visitor_from');
        $user->status = '1';
        $user->added_to_facility = Carbon::now();
        $user->twr_solo_fields = '';
        $user->twr_solo_expires = '';
        $user->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' added the visitor '.$user->full_name.'.';
        $audit->save();
        
        // Add to the VATUSA roster
        $client = new Client();
        $res = $client->request('POST', Config::get('vatusa.base').'/v2/facility/'.Config::get('vatusa.facility').'/roster/manageVisitor/'.$request->input('cid').'?apikey='.Config::get('vatusa.api_key'), ['http_errors' => false]);

        return redirect('/dashboard/admin/roster/visit/requests')->with('success', 'The visitor has been successfully added to the roster.');
    }

    public function removeVisitor($id) {
        $user = User::find($id);
        $name = $user->full_name;
        if ($user->visitor == 0) {
            return redirect()->back()->with('error', 'You can only remove visitors this way. If you are trying to remove a home controller, please do this from the VATUSA website.');
        } else {
            $event_requests = EventRegistration::where('controller_id', $user->id)->get();
            foreach ($event_requests as $e) {
                $e->delete();
            }
            $user->status = 2;
            $user->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' removed the visitor '.$name.'.';
            $audit->save();
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) { // Added this to deal with case when user does not have an email address on file
                Mail::send('emails.remove_visitor', ['user' => $user], function ($message) use ($user) {
                    $message->from('info@notams.ztlartcc.org', 'vZTL ARTCC Staff')->subject('Notification of ZTL Roster Removal');
                    $message->to($user->email)->cc('datm@ztlartcc.org');
                });
            }
            // Remove on the VATUSA roster
            $client = new Client();
            $req_params = [ 'form_params' =>
                [
                    'reason' => 'IAW ZTL ARTCC Facility Administrative Policy'
                ],
                'http_errors' => false
            ];
            $res = $client->request('DELETE', Config::get('vatusa.base').'/v2/facility/'.Config::get('vatusa.facility').'/roster/manageVisitor/'.$id.'?apikey='.Config::get('vatusa.api_key'), $req_params);
            
            return redirect('/dashboard/controllers/roster')->with('success', 'The visitor has been removed successfully.');
        }
    }

    public function viewCalendar() {
        $calendar = Calendar::where('type', '1')->get()->sortByDesc(function ($news) {
            return strtotime($news->date.' '.$news->time);
        });
        $news = Calendar::where('type', '2')->get()->sortByDesc(function ($news) {
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
        $calendar->title = $request->input('title');
        $calendar->date = $request->input('date');
        $calendar->time = $request->input('time');
        $calendar->body = $request->input('body');
        $calendar->type = $request->input('type');
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

        $calendar->title = $request->input('title');
        $calendar->date = $request->input('date');
        $calendar->time = $request->input('time');
        $calendar->body = $request->input('body');
        $calendar->type = $request->input('type');
        $calendar->updated_by = Auth::id();
        $calendar->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' edited the calendar event '.$calendar->title.'.';
        $audit->save();

        return redirect('/dashboard/admin/calendar')->with('success', 'The calendar event or news posting has been edited.');
    }

    public function deleteCalendarEvent($id) {
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

    public function toggleCalenderEventVisibilty($id) {
        $calendar = Calendar::find($id);
        $type = '';

        if ($calendar->visible == 1) {
            $calendar->visible = 0;
            $type = 'invisible';
        } elseif ($calendar->visible == 0) {
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
        $name = preg_replace('/^[\w\-. ]+$/', '', $request->title).'_'.$time.'.'.$ext;

        $path = $request->file('file')->storeAs(
            '/public/files',
            $name
        );

        $public_url = '/storage/files/'.$name;
        $permalink = $request->input('permalink');
        if (strlen($permalink) < 1) {
            $permalink = null;
        }

        $file = new File;
        $file->name = $request->input('title');
        $file->type = $request->input('type');
        $file->desc = $request->input('desc');
        $file->path = $public_url;
        $file->permalink = $permalink;
        $file->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' created the file '.$file->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/files')->with('success', 'The file has been successfully added.');
    }

    public function fileSeparator(Request $request) {
        $file = new File;
        $file->name = $request->input('title');
        $file->type = $request->input('type');
        $file->path = '';
        $file->row_separator = 1;
        $file->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' created the file separator '.$file->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/files')->with('success', 'The file separator has been successfully added.');
    }

    public function editFile($id) {
        $file = File::find($id);

        return view('dashboard.admin.files.edit')->with('file', $file);
    }

    public function saveFile(Request $request, $id) {
        $permalink = $request->input('permalink');
        if (strlen($permalink) < 1) {
            $permalink = null;
        }
        $file = File::find($id);
        $file->name = $request->input('title');
        $file->type = $request->input('type');
        $file->desc = $request->input('desc');
        $file->permalink = $permalink;
        $file->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated the file '.$file->name.'.';
        $audit->save();

        return redirect('/dashboard/controllers/files')->with('success', 'The file has been edited successfully.');
    }
    
    public function updateFileDispOrder(Request $request) {
        if (($request->act == 'up')&&($request->pos > 0)) { // If action is move up, swap spots with item that = -1
            $file = File::where('type', $request->typ)->where('disp_order', $request->pos - 1)->first();
            $file->disp_order = $request->pos;
            $file->timestamps = false;
            $file->save();
            $file = File::find($request->id);
            $file->disp_order = $request->pos - 1;
            $file->timestamps = false;
            $file->save();
        } elseif ($request->act == 'down') { // If action is move down, then +1 to all elements >= order and update
            $file = File::where('type', $request->typ)->where('disp_order', $request->pos + 1)->first();
            if (!is_null($file)) {
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
        foreach ($files as $f) {
            $dispString .= "<tr>";
            if ($f->row_separator) {
                $dispString .= "<th class=\"text-center\" colspan=\"3\">$f->name</th>";
            } else {
                $dispString .= "<td>$f->name</td>
                                <td>$f->desc</td>
                                <td>$f->updated_at</td>";
            }
            $dispString .=     "<td>
								<div class=\"btn-group\">";
            if (! $f->row_separator) {
                $dispString .= "<a href=\"$f->path\" target=\"_blank\" class=\"btn btn-success simple-tooltip\" data-toggle=\"tooltip\" title=\"Download\"><i class=\"fas fa-download\"></i></a>";
            }
            $dispString .= "<a href=\"/dashboard/admin/files/edit/$f->id\" class=\"btn btn-warning simple-tooltip\" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fas fa-pencil-alt\"></i></a>
                            <a href=\"/dashboard/admin/files/delete/$f->id\" onclick=\"return confirm(\'Are you sure you want to delete " . $f->name . "?\')\" class=\"btn btn-danger simple-tooltip\" data-toggle=\"tooltip\" title=\"Delete\"><i class=\"fas fa-times\"></i></a>";
            if ($f->disp_order > 0) { // Don't show the up button for the first item listed
                $dispString .= "<a onclick=\"itemReorder($f->id,$f->disp_order,$f->type,\'up\');\" class=\"btn btn-info simple-tooltip\" data-toggle=\"tooltip\" title=\"Up\"><i class=\"fas fa-arrow-up\"></i></a>";
            }
            if (count($files) > $f->disp_order + 1) { // Don't show the down button for the last item listed
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
        $feedbackOptions = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        $events = Event::all()->pluck('date', 'id');
        $eventsList = [];
        foreach ($events as $evId => $event) {
            $eventDate = Carbon::createFromFormat('m/d/Y', substr($event, 0, 10));
            if ((Carbon::today() >= $eventDate) && (Carbon::today() <= $eventDate->copy()->addDays(45))) {
                $eventsList[] = $evId;
            }
        }
        unset($events);
        $events = Event::whereIn('id', $eventsList)->orderBy('name', 'DESC')->get()->pluck('name', 'id');
        foreach ($events as $evId => $event) {
            $feedbackOptions->prepend('Event: ' . $event, $evId);
        }
        $feedbackOptions->prepend('General ATC Feedback', '0');

        $feedback = Feedback::where('status', 0)->orderBy('created_at', 'ASC')->get();
        $feedback_p = Feedback::where('status', 1)->orwhere('status', 2)->orderBy('updated_at', 'DESC')->paginate(25);
        return view('dashboard.admin.feedback')->with('feedback', $feedback)->with('feedback_p', $feedback_p)->with('feedbackOptions', $feedbackOptions);
    }

    public function saveFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->feedback_id = $request->feedback_id;
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

        $controller = User::find($feedback->feedback_id);
        if (isset($controller)) {
            Mail::send(['html' => 'emails.new_feedback'], ['feedback' => $feedback, 'controller' => $controller], function ($m) use ($feedback, $controller) {
                $m->from('feedback@notams.ztlartcc.org', 'vZTL ARTCC Feedback Department');
                $m->subject('You Have New Feedback!');
                $m->to($controller->email);
            });
        }
        return redirect()->back()->with('success', 'The feedback has been saved.');
    }

    public function hideFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->feedback_id = $request->feedback_id;
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
        $feedback->feedback_id = $request->feedback_id;
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

        Mail::send('emails.feedback_email', ['feedback' => $feedback, 'body' => $body, 'sender' => $sender], function ($m) use ($feedback, $subject, $replyTo, $replyToName) {
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

        if ($bulk == null) {
            $to = User::find($request->to)->email;
            $emails = [$to];
        } elseif ($bulk == 0) {
            $emails = $controllers;
        } elseif ($bulk == 1) {
            $emails = $hcontrollers;
        } elseif ($bulk == 2) {
            $emails = $vcontrollers;
        } elseif ($bulk == 3) {
            $emails = $mentors;
        } elseif ($bulk == 4) {
            $emails = $ins;
        } elseif ($bulk == 5) {
            $emails = $train_staff;
        } elseif ($bulk == 6) {
            $emails = $obs;
        } elseif ($bulk == 7) {
            $emails = $s1;
        } elseif ($bulk == 8) {
            $emails = $s2;
        } elseif ($bulk == 9) {
            $emails = $s3;
        } elseif ($bulk == 10) {
            $emails = $c1;
        } else {
            return redirect()->back()->with('error', 'Please select either a controller or a group to send an email to.');
        }

        //Sends to all recipients
        foreach ($emails as $e) {
            if ($e != 'No email') {
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

    public function showBronzeMic($sort = 'bronzesort', $year = null, $month = null) {
        if ($year == null) {
            $year = date('y');
        }

        if ($month == null) {
            $month = date('n');
        }

        $stats = ControllerLog::aggregateAllControllersByPosAndMonth($year, $month);
        $year_stats = ControllerLog::aggregateAllControllersByPosAndYear($year, $month);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $winner = Bronze::where('month', $month)->where('year', $year)->first();
        $winner_local = LocalHero::where('month', $month)->where('year', $year)->first();

        $challenge = LocalHeroChallenges::where('month', $month)->where('year', $year)->first();
        if ($challenge) {
            $challenge->positions = explode(',', $challenge->positions);
        } else {
            $challenge = new LocalHeroChallenges;
            $challenge->id = -1;
        }
        $local_hero_pos = LocalHeroChallenges::getLocalHeroChallengePositions();
        $local_hero_positions = [];
        foreach ($local_hero_pos as $pos) {
            $local_hero_positions[$pos] = $pos;
        }

        if ($sort == 'pyritesort') {
            $home = $homec->sortByDesc(function ($user) use ($year_stats) {
                return $year_stats[$user->id]->bronze_hrs;
            });
        } else {
            $home = $homec->sortByDesc(function ($user) use ($stats, $sort) {
                if ($sort == 'localsort') {
                    return $stats[$user->id]->local_hero_hrs;
                }
                return $stats[$user->id]->bronze_hrs;
            });
        }
        
        return view('dashboard.admin.bronze-mic')->with('all_stats', $all_stats)->with('year', $year)->with('sort', $sort)
                                                  ->with('month', $month)->with('stats', $stats)->with('year_stats', $year_stats)
                                                  ->with('home', $home)->with('winner', $winner)->with('winner_local', $winner_local)
                                                  ->with('challenge', $challenge)->with('local_hero_challenge_positions', $local_hero_positions);
    }

    public function setLocalHeroWinner($year, $month, $hours, $id) {
        $local_hero = new LocalHero;
        $local_hero->controller_id = $id;
        $local_hero->month = $month;
        $local_hero->year = $year;
        $local_hero->month_hours = $hours;
        $local_hero->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' set the local hero winner for '.$month.'/'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/bronze-mic/localsort/'.$year.'/'.$month)->with('success', 'The controller has been set as the local hero winner successfully.');
    }

    public function removeLocalHeroWinner($id, $year, $month) {
        $local_hero = LocalHero::find($id);
        $local_hero->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed the local hero winner for '.$month.'/'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/bronze-mic/localsort/'.$year.'/'.$month)->with('success', 'The local hero winner has been removed successfully.');
    }

    public function updateLocalHeroChallenge(Request $request, $id) {
        $validator = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $local_hero_challenge = LocalHeroChallenges::find($id);
        if (!$local_hero_challenge) {
            $local_hero_challenge = new LocalHeroChallenges;
            $local_hero_challenge->year = $request->year;
            $local_hero_challenge->month = $request->month;
            $news = new Calendar;
        } else {
            $news = Calendar::find($local_hero_challenge->news_id);
            if (!$news) {
                $news = new Calendar;
            }
        }
        $news_pre_title = Carbon::create()->day(1)->month($request->month)->format('F') . " Challenge: ";
        $news->title = $news_pre_title . $request->title;
        $news->date = Carbon::now()->format('m/d/Y');
        $news->body = $request->description;
        $news->created_by = Auth::id();
        $news->type = 2;
        $news->visible = ($request->postToNews == 1) ? 1 : 0;
        $news->save();

        $local_hero_challenge->positions = is_array($request->positions) ? implode(',', $request->positions) : '';
        $local_hero_challenge->title = $request->title;
        $local_hero_challenge->description = $request->description;
        $local_hero_challenge->news_id = $news->id;
        $local_hero_challenge->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated the local hero configuration for '.$request->month.'/'.$request->year.'.';
        $audit->save();

        return redirect('/dashboard/admin/bronze-mic/localsort/'.$request->year.'/'.$request->month)->with('success', 'Local hero configuration settings were saved.');
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

        return redirect('/dashboard/admin/bronze-mic/bronzesort/'.$year.'/'.$month)->with('success', 'The controller has been set as the bronze mic winner successfully.');
    }

    public function removeBronzeWinner($id, $year, $month) {
        $bronze = Bronze::find($id);
        $bronze->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed the bronze mic winner for '.$month.'/'.$year.'.';
        $audit->save();

        return redirect('/dashboard/admin/bronze-mic/bronzesort/'.$year.'/'.$month)->with('success', 'The bronze mic winner has been removed successfully.');
    }

    public function showPyriteMic($year = null) {
        if ($year == null) {
            $year = date('y');
        }

        $year_stats = ControllerLog::aggregateAllControllersByPosAndYear($year);
        $all_stats = ControllerLog::getAllControllerStats();

        $homec = User::where('visitor', 0)->where('status', 1)->get();
        $winner = Pyrite::where('year', $year)->first();

        $home = $homec->sortByDesc(function ($user) use ($year_stats) {
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
            'description' => 'required',
            'banner_url' => 'nullable|url'
        ]);

        $public_url = null;
        
        if ($request->file('banner') != null && $request->filled('banner_url')) {
            return redirect()->back()->withErrors('Please ensure you submit only one of the following: a URL or a file for the banner.')->withInput();
        }
        if ($request->file('banner') != null) {
            $ext = $request->file('banner')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('banner')->storeAs(
                'public/event_banners',
                $time.'.'.$ext
            );
            $public_url = '/storage/event_banners/'.$time.'.'.$ext;
        } elseif ($request->filled('banner_url')) {
            try {
                $response = Http::get($request->banner_url);
                if (!$response->successful()) {
                    throw new ConnectionException;
                }
            } catch (RequestException | ConnectionException) {
                return redirect()->back()->withErrors('The provided URL is not valid or unreachable')->withInput();
            }
            $imageContent = file_get_contents($request->banner_url);
            $imageSize = getimagesizefromstring($imageContent);
            if ($imageSize == false) {
                return redirect()->back()->withErrors('The provided URL does not point to an image.')->withInput();
            }
            $time = Carbon::now()->timestamp;
            $ext = pathinfo($request->banner_url, PATHINFO_EXTENSION);
            file_put_contents(storage_path('app/public/event_banners/'.$time.'.'.$ext), $imageContent);
            $public_url = '/storage/event_banners/'.$time.'.'.$ext;
        }

        $event = new Event;
        $event->name = $request->name;
        $event->host = $request->host;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->status = 0;
        $event->reg = 0;
        $event->type = $request->type;
        $event->save();
        try {
            $event->banner_path = $public_url;
            $event->reduceEventBanner();
            $event->save();
        } catch (\Exception $e) {
            return redirect('/dashboard/controllers/events/view/'.$event->id)->with('error', 'The event has been created successfully, but the banner image appears to be corrupt. Please re-save the image and ensure that it is not an animated image.');
        }
    
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
            'description' => 'required',
            'banner_url' => 'nullable|url'
        ]);

        $event = Event::find($id);
        $public_url = $event->banner_path;

        if ($request->type == 1) { // if we are setting it to a verified support event, verify the banner
            if (starts_with($event->banner_path, "http://") || starts_with($event->banner_path, "https://")) {
                // download the banner
                $public_url = '/event_banners/vatsim_'.$event->vatsim_id.substr($event->banner_path, -4);
                Storage::disk('public')->put($public_url, file_get_contents($event->banner_path));
                $event->banner_path = $public_url;
                try {
                    $event->reduceEventBanner();
                } catch (\Exception $e) {
                    return redirect('/dashboard/controllers/events/view/'.$event->id)->with('error', 'The event has been created successfully, but the banner image appears to be corrupt. Please re-save the image and ensure that it is not an animated image.');
                }
                $event->banner_path = '/storage'.$public_url;
            }
        }
        if ($request->file('banner') != null && $request->filled('banner_url')) {
            return redirect()->back()->withErrors('Please ensure you submit only one of the following: a URL or a file for the banner.')->withInput();
        }
        if ($request->file('banner') != null) {
            $ext = $request->file('banner')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('banner')->storeAs(
                'public/event_banners',
                $time.'.'.$ext
            );
            $public_url = '/storage/event_banners/'.$time.'.'.$ext;
        } elseif ($request->filled('banner_url')) {
            try {
                $response = Http::get($request->banner_url);
                if (!$response->successful()) {
                    throw new ConnectionException;
                }
            } catch (RequestException | ConnectionException) {
                return redirect()->back()->withErrors('The provided URL is not valid or unreachable')->withInput();
            }
            $imageContent = file_get_contents($request->banner_url);
            $imageSize = getimagesizefromstring($imageContent);
            if ($imageSize == false) {
                return redirect()->back()->withErrors('The provided URL does not point to an image.')->withInput();
            }
            $time = Carbon::now()->timestamp;
            $ext = pathinfo($request->banner_url, PATHINFO_EXTENSION);
            file_put_contents(storage_path('app/public/event_banners/'.$time.'.'.$ext), $imageContent);
            $public_url = '/storage/event_banners/'.$time.'.'.$ext;
        }

        $event->name = $request->name;
        $event->host = $request->host;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->status = 0;
        $event->type = $request->type;
        $event->save();
        try {
            $event->banner_path = $public_url;
            $event->reduceEventBanner();
            $event->save();
        } catch (\Exception $e) {
            return redirect('/dashboard/controllers/events/view/'.$event->id)->with('error', 'The event has been created successfully, but the banner image appears to be corrupt. Please re-save the image and ensure that it is not an animated image.');
        }

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

        foreach ($reg as $r) {
            $r->delete();
        }
        foreach ($positions as $r) {
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
        foreach ($requests as $r) {
            $r->delete();
        }

        return redirect()->back()->with('success', 'The position has been removed successfully.');
    }

    public function toggleRegistration($id): RedirectResponse {
        $event = Event::find($id);

        if (! $event) {
            return redirect()->back()->with('error', 'That event does not exist');
        }

        $event->toggleRegistration();

        return redirect()->back()->with('success', 'The registration has been toggled successfully.');
    }

    public function toggleShowAssignments($id): RedirectResponse {
        $event = Event::find($id);
        if (! $event) {
            return redirect()->back()->with('error', 'That event does not exist');
        }

        $event->toggleShowAssignments();

        return redirect()->back()->with('success', 'The assignment visibility has been toggled successfully.');
    }

    public function assignPosition(Request $request, $id) {
        $reg = EventRegistration::find($id);
        $reg->position_id = $request->position;
        $reg->position_id_detail = $request->position_detail;
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

    public function eventMarkNoShow($id) {
        $position = EventRegistration::find($id);
        $position->no_show = 1;
        $position->save();

        return redirect()->back()->with('success', 'Controller was marked as a no-show.');
    }

    public function eventUnMarkNoShow($id) {
        $position = EventRegistration::find($id);
        $position->no_show = 0;
        $position->save();

        return redirect()->back()->with('success', 'Controller was unmarked as a no-show.');
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
        $reg->position_id_detail = $request->position_detail;
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
        $positions = EventPosition::where('event_id', $id)->orderBy('id', 'DESC')->get();
        $last_preset_position = PresetPosition::orderBy('id', 'DESC')->first()->id;
        $last = $last_preset_position + 1;
        $preset_positions = $positions->count() + $last_preset_position;

        $position_preset = new PositionPreset;
        $position_preset->name = $request->name;
        $position_preset->first_position = $last;
        $position_preset->last_position = $preset_positions;
        $position_preset->save();

        foreach ($positions as $p) {
            $preset = new PresetPosition;
            $preset->name = $p->name;
            $preset->save();
        }

        return redirect()->back()->with('success', 'The position preset has been added successfully');
    }

    public function sendEventReminder($id) {
        Artisan::call('Event:SendEventReminder ' . $id);
        return redirect()->back()->with('success', 'Event reminder sent');
    }

    public function retrievePositionPreset(Request $request, $id) {
        $preset = PositionPreset::find($request->p_id);
        $first = $preset->first_position;
        $last = $preset->last_position;

        $ids = range($first, $last);

        $presets = PresetPosition::find($ids);

        foreach ($presets as $p) {
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
        $new_reports = Incident::where('status', 0)->orderBy('created_at', 'DESC')->get();
        $archive_reports = Incident::where('status', 1)->orderBy('created_at', 'DESC')->paginate(20);

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
        $audits = Audit::orderBy('created_at', 'DESC')->paginate(50);
        return view('dashboard.admin.audits')->with('audits', $audits);
    }

    public function showFeatureToggles() {
        $toggles = FeatureToggle::orderBy('created_at', 'desc')->get();

        return view('dashboard.admin.toggles.show')->with('toggles', $toggles);
    }

    public function showCreateFeatureToggle() {
        return view('dashboard.admin.toggles.create');
    }

    public function createFeatureToggle(Request $request) {
        $request->merge([
            'toggle_name' => preg_replace('/\s+/', '_', trim($request->input('toggle_name')))
        ])->validate([
            'toggle_name' => 'required|unique:feature_toggles'
        ]);

        $toggle = new FeatureToggle;
        $toggle->toggle_name = $request->input('toggle_name');
        $toggle->toggle_description = $request->input('toggle_description');
        $toggle->save();

        return redirect('/dashboard/admin/toggles')->with('success', 'The toggle `' . $toggle->toggle_name . '` has been created');
    }

    public function showEditFeatureToggle($toggle_name) {
        $toggle = FeatureToggle::find($toggle_name);
        return view('dashboard.admin.toggles.edit')->with('t', $toggle);
    }

    public function editFeatureToggle(Request $request) {
        $request->merge(['toggle_name' => preg_replace('/\s+/', '_', trim($request->input('toggle_name')))]);
        if ($request->input('toggle_name') != $request->input('toggle_name_orig')) {
            $request->validate(['toggle_name' => 'required|unique:feature_toggles']);
        }
        FeatureToggle::updateToggle($request->input('toggle_name_orig'), $request->input('toggle_name'), $request->input('toggle_description'));

        return redirect('/dashboard/admin/toggles')->with('success', 'The toggle `' . $request->input('toggle_name') . '` has been updated');
    }

    public function toggleFeatureToggle($toggle_name) {
        FeatureToggle::toggle($toggle_name);

        return redirect()->back();
    }

    public function deleteFeatureToggle($toggle_name) {
        $result = FeatureToggle::deleteToggle($toggle_name);
        if ($result) {
            return redirect('/dashboard/admin/toggles')->with('success', 'The toggle `' . $toggle_name . '` has been deleted');
        } else {
            return redirect('/dashboard/admin/toggles')->with('error', 'The toggle `' . $toggle_name . '` could not be deleted');
        }
    }

    public function backgroundMonitor() {
        $tasks = ScheduleMonitorTasks::getTasks();
        $format = Config::get('schedule-monitor.date_format');
        return view('dashboard.admin.background-monitor')->with('tasks', $tasks)->with('format', $format);
    }
}
