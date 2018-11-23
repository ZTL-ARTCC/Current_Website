<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Announcement;
use App\Bronze;
use App\Calendar;
use App\ControllerLog;
use App\Event;
use App\EventPosition;
use App\EventRegistration;
use App\Feedback;
use App\File;
use App\Metar;
use App\PositionPreset;
use App\PresetPosition;
use App\Scenery;
use App\User;
use App\Visitor;
use Artisan;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mail;
use Storage;

use GuzzleHttp\Client;
use App\TrainingTicket;

class AdminDash extends Controller
{
    public function showScenery() {
        $scenery = Scenery::orderBy('airport', 'ASC')->get();

        $fsx = $scenery->where('sim', 0);
        $xp = $scenery->where('sim', 1);

        return view('dashboard.admin.scenery.index')->with('fsx', $fsx)->with('xp', $xp);
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

        return redirect('/dashboard/admin/scenery')->with('success', 'Scenery edited successfully.');
    }

    public function deleteScenery($id) {
        $scenery = Scenery::find($id);
        $scenery->delete();

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
        $visitc = User::where('visitor', 1)->where('status', 1)->orderBy('lname', 'ASC')->get();
        $trainc = User::orderBy('lname', 'ASC')->get()->filter(function($user){
            return $user->hasRole('mtr') || $user->hasRole('ins');
        });

        return view('dashboard.admin.roster.purge')->with('stats', $stats)->with('homec', $homec)->with('visitc', $visitc)
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
            $user->twr = Input::get('twr');
            $user->app = Input::get('app');
            $user->ctr = Input::get('ctr');
            $user->initials = Input::get('initials');
            $user->train_pwr = Input::get('train_pwr');
            $user->monitor_pwr = Input::get('monitor_pwr');
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
            $user->twr = Input::get('twr');
            $user->app = Input::get('app');
            $user->ctr = Input::get('ctr');
            $user->save();
        }

        return redirect('/dashboard/controllers/roster')->with('success', 'Controller updated successfully.');

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

        Mail::send('emails.visit.accept', ['visitor' => $visitor], function($message) use ($visitor){
            $message->from('visitors@ztlartcc.org', 'vZTL ARTCC Visiting Department')->subject('Visitor Request Accepted');
            $message->to($visitor->email)->cc('datm@ztlartcc.org');
        });

        $parts = explode(" ",$visitor->name);
        $fname = $parts[0];
        $lname = $parts[1];

        $users_inc_v = User::get();
        $fn_initial = substr($fname, 0, 1);
        $ln_initial = substr($lname, 0, 1);

        a:
        $initials = $fn_initial.$ln_initial;
        $yes = 1;
        foreach($users_inc_v as $u) {
            if($u->initials == $initials) {
                $yes = 0;
                break;
            } else {
                $yes = 1;
            }
        }

        if($yes === 1) {
            $initials = $initials;
        } else {
            $fi_int = rand(1, 26);
            $li_int = rand(1, 26);

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

            if($li_int == 1) {
                $ln_initial = 'A';
            } elseif($li_int == 2) {
                $ln_initial = 'B';
            } elseif($li_int == 3) {
                $ln_initial = 'C';
            } elseif($li_int == 4) {
                $ln_initial = 'D';
            } elseif($li_int == 5) {
                $ln_initial = 'E';
            } elseif($li_int == 6) {
                $ln_initial = 'F';
            } elseif($li_int == 7) {
                $ln_initial = 'G';
            } elseif($li_int == 8) {
                $ln_initial = 'H';
            } elseif($li_int == 9) {
                $ln_initial = 'I';
            } elseif($li_int == 10) {
                $ln_initial = 'J';
            } elseif($li_int == 11) {
                $ln_initial = 'K';
            } elseif($li_int == 12) {
                $ln_initial = 'L';
            } elseif($li_int == 13) {
                $ln_initial = 'M';
            } elseif($li_int == 14) {
                $ln_initial = 'N';
            } elseif($li_int == 15) {
                $ln_initial = 'O';
            } elseif($li_int == 16) {
                $ln_initial = 'P';
            } elseif($li_int == 17) {
                $ln_initial = 'Q';
            } elseif($li_int == 18) {
                $ln_initial = 'R';
            } elseif($li_int == 19) {
                $ln_initial = 'S';
            } elseif($li_int == 20) {
                $ln_initial = 'T';
            } elseif($li_int == 21) {
                $ln_initial = 'U';
            } elseif($li_int == 22) {
                $ln_initial = 'V';
            } elseif($li_int == 23) {
                $ln_initial = 'W';
            } elseif($li_int == 24) {
                $ln_initial = 'X';
            } elseif($li_int == 25) {
                $ln_initial = 'Y';
            } elseif($li_int == 26) {
                $ln_initial = 'Z';
            }

            goto a;
        }

        return view('dashboard.admin.roster.new_vis')->with('visitor', $visitor)->with('initials', $initials)->with('fname', $fname)->with('lname', $lname);
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
            $message->from('visitors@ztlartcc.org', 'vZTL ARTCC Visiting Department')->subject('Visitor Request Rejected');
            $message->to($visitor->email)->cc('datm@ztlartcc.org');
        });

        return redirect('/dashboard/admin/roster/visit/requests')->with('success', 'The visit request has been rejected successfully.');
    }

    public function storeVisitor(Request $request, $id) {
        $user = new User;
        $user->id = $id;
        $user->fname = Input::get('fname');
        $user->lname = Input::get('lname');
        $user->email = Input::get('email');
        $user->initials = Input::get('initials');
        $user->rating_id = Input::get('rating_id');
        $user->visitor = '1';
        $user->visitor_from = Input::get('visitor_from');
        $user->status = '1';
        $user->added_to_facility = Carbon::now();
        $user->save();

        return redirect('/dashboard/admin/roster/visit/requests')->with('success', 'The visitor has been successfully added to the roster.');
    }

    public function removeVisitor($id) {
        $user = User::find($id);
        if($user->visitor == 0) {
            return redirect()->back()->with('error', 'You can only remove visitors this way. If you are trying to remove a home controller, please do this from the VATUSA website.');
        } else {
            $user->delete();
            return redirect('/dashboard/controllers/roster')->with('success', 'The visitor has been removed successfully.');
        }
    }

    public function viewCalendar() {
        $calendar = Calendar::where('type', '1')->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();
        $news = Calendar::where('type', '2')->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();

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

        return redirect('/dashboard/admin/calendar')->with('success', 'The calendar event or news posting has been edited.');
    }

    public function deleteCalendarEvent($id){
        $calendar = Calendar::find($id);
        $calendar->delete();

        return redirect('/dashboard/admin/calendar')->with('success', 'The calendar event or news posting has been deleted.');
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

        $path = $request->file('file')->storeAs(
            'files', $request->title.'_'.$time.'.'.$ext
        );

        $public_url = Config::get('app.app_storage').$path;

        $file = new File;
        $file->name = Input::get('title');
        $file->type = Input::get('type');
        $file->desc = Input::get('desc');
        $file->path = $public_url;
        $file->save();

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

        return redirect('/dashboard/controllers/files')->with('success', 'The file has been edited successfully.');
    }

    public function deleteFile($id) {
        $file = File::find($id);
        $file_path = $file->path;
        $file->delete();
        return redirect()->back()->with('success', 'The file has been deleted successfully.');
    }

    public function showFeedback() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        $feedback = Feedback::where('status', 0)->orderBy('created_at', 'ASC')->get();
        $feedback_p = Feedback::where('status', 1)->orwhere('status', 2)->orderBy('updated_at', 'ASC')->get();
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

        $controller = User::find($feedback->controller_id);

        Mail::send(['html' => 'emails.new_feedback'], ['feedback' => $feedback, 'controller' => $controller], function($m) use ($feedback, $controller) {
            $m->from('feedback@ztlartcc.org', 'vZTL ARTCC Feedback Department');
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

        return redirect()->back()->with('success', 'The feedback has been updated.');
    }

    public function sendNewEmail() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        return view('dashboard.admin.email.send')->with('controllers', $controllers);
    }

    public function sendEmail(Request $request) {
        $controllers = User::where('status', 1)->where('opt', 1)->orderBy('lname', 'ASC')->get()->pluck('email');
        $hcontrollers = User::where('status', 1)->where('opt', 1)->where('visitor', 0)->orderBy('lname', 'ASC')->get()->pluck('email');
        $vcontrollers = User::where('status', 1)->where('opt', 1)->where('visitor', 1)->orderBy('lname', 'ASC')->get()->pluck('email');
        $mentors = User::where('opt', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'mtr');
        })->get()->pluck('email');
        $ins = User::where('opt', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'ins');
        })->get()->pluck('email');
        $train_staff = User::where('opt', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'mtr')->orWhere('name', 'ins');
        })->get()->pluck('email');
        $sender = Auth::user();
        $name = $request->name;
        $bulk = $request->bulk;
        $subject = $request->subject;
        $body = $request->message;

        if($bulk == null) {
            $to = User::find($request->to)->email;
            $emails = [$to];
        } elseif($bulk == 1) {
            $emails = $controllers;
        } elseif($bulk == 2) {
            $emails = $hcontrollers;
        } elseif($bulk == 3) {
            $emails = $vcontrollers;
        } elseif($bulk == 4) {
            $emails = $mentors;
        } elseif($bulk == 5) {
            $emails = $ins;
        } elseif($bulk == 6) {
            $emails = $train_staff;
        } else {
            return redirect()->back()->with('error', 'Please select either a controller or a group to send an email to.');
        }

        foreach($emails as $e){
            Mail::send(['html' => 'emails.send'], ['sender' => $sender, 'body' => $body], function ($m) use ($name, $subject, $e, $sender) {
                $m->from('info@ztlartcc.org', $name);
                $m->subject('[vZTL ARTCC] '.$subject);
                $m->to($e)->bcc($sender->email);
            });
        }

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

        return redirect('/dashboard/admin/bronze-mic/'.$year.'/'.$month)->with('success', 'The controller has been set as the bronze mic winner successfully.');
    }

    public function removeBronzeWinner($id, $year, $month) {
        $bronze = Bronze::find($id);
        $bronze->delete();

        return redirect('/dashboard/admin/bronze-mic/'.$year.'/'.$month)->with('success', 'The winner has been removed successfully.');
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
        $date = new Carbon($request->date);

        $event = new Event;
        $event->name = $request->name;
        $event->host = $request->host;
        $event->description = $request->description;
        $event->date = $date->format('m/d/Y');
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->banner_path = $public_url;
        $event->status = 0;
        $event->reg = 0;
        $event->save();

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
        $date = new Carbon($request->date);

        $event->name = $request->name;
        $event->host = $request->host;
        $event->description = $request->description;
        $event->date = $date->format('m/d/Y');
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->banner_path = $public_url;
        $event->status = 0;
        $event->save();

        return redirect('/dashboard/controllers/events/view/'.$event->id)->with('success', 'The event has been edited successfully.');
    }

    public function deleteEvent($id) {
        $event = Event::find($id);
        $positions = EventPosition::where('event_id', $event->id)->get();
        $reg = EventRegistration::where('event_id', $event->id)->get();

        foreach($reg as $r) {
            $r->delete();
        }
        foreach($positions as $r) {
            $r->delete();
        }

        $event->delete();
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
        } elseif($event->reg == 1) {
            $event->reg = 0;
            $event->save();
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

        return redirect()->back()->with('success', 'The event has been unhidden successfully.');
    }

    public function hideEvent($id) {
        $event = Event::find($id);
        $event->status = 0;
        $event->save();

        return redirect()->back()->with('success', 'The event has been hidden successfully.');
    }

    public function setEventPositionPreset(Request $request, $id) {
        $positions = EventPosition::where('event_id', $id)->get();
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

    public function updateTrainingTickets() {
        $client = new Client();
        $response = $client->request('GET', 'https://ztlartcc.org/trainingticket-transfer?t=1364926');
        $r = json_decode($response->getBody());

        foreach($r as $r) {
            $already_exists = TrainingTicket::where('created_at', $r->created_at)->where('controller_id', $r->controller_id)->get();
            if($already_exists->count() > 0) {
            } else {
                $ticket = new TrainingTicket;
                $ticket->controller_id = $r->controller_id;
                $ticket->trainer_id = $r->instructor_id;

                if($r->position == 1 || $r->position == 3) {
                    $ticket->position = 0;
                } elseif($r->position == 5) {
                    $ticket->position = 1;
                } elseif($r->position == 2 || $r->position == 4) {
                    $ticket->position = 2;
                } elseif($r->position == 6) {
                    $ticket->position = 3;
                } elseif($r->position == 7) {
                    $ticket->position = 4;
                } elseif($r->position == 8) {
                    $ticket->position = 5;
                } elseif($r->position == 9) {
                    $ticket->position = 6;
                }

                if($r->type == 1) {
                    $ticket->type = 0;
                } elseif($r->type == 2) {
                    $ticket->type = 3;
                } elseif($r->type == 3) {
                    $ticket->type = 1;
                } elseif($r->type == 4) {
                    $ticket->type = 8;
                } elseif($r->type == 5) {
                    $ticket->type = 9;
                }

                $ticket->date = Carbon::parse($r->date)->format('m/d/Y');
                $ticket->start_time = $r->session_begin;
                $ticket->end_time = 'N/A, Ticket Submitted Before 12/4/2018';
                $duration = round($r->duration / 60);
                if(strlen($duration) == 1) {
                    $ticket->duration = '0'.$duration.':00';
                } else {
                    $ticket->duration = $duration.':00';
                }

                $ticket->comments = $r->comments;
                $ticket->ins_comments = $r->staff_notes;
                $ticket->created_at = $r->created_at;
                $ticket->updated_at = $r->updated_at;
                $ticket->save();
            }
        }
    }
}
