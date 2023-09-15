<?php

namespace App\Http\Controllers;

use App\Audit;
use App\Ots;
use App\PublicTrainingInfo;
use App\PublicTrainingInfoPdf;
use App\TrainingInfo;
use App\TrainingTicket;
use App\User;
use Auth;
use Carbon\Carbon;
use Config;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Mail;
use mitoteam\jpgraph\MtJpGraph;

class TrainingDash extends Controller {
    public static $GRAPH_SESSIONS_PER_MONTH = 1;
    public static $GRAPH_SESSIONS_BY_INSTRUCTOR = 2;
    public static $GRAPH_SESSION_AVERAGE_DURATION = 3;
    public static $GRAPH_STUDENT_TRAINING_PER_RATING = 4;

    public function showATCast() {
        return view('dashboard.training.atcast');
    }

    public function trainingInfo() {
        $info_minor_gnd = TrainingInfo::where('section', 0)->orderBy('number', 'ASC')->get();
        $info_minor_lcl = TrainingInfo::where('section', 1)->orderBy('number', 'ASC')->get();
        $info_minor_app = TrainingInfo::where('section', 2)->orderBy('number', 'ASC')->get();
        $info_major_gnd = TrainingInfo::where('section', 3)->orderBy('number', 'ASC')->get();
        $info_major_lcl = TrainingInfo::where('section', 4)->orderBy('number', 'ASC')->get();
        $info_major_app = TrainingInfo::where('section', 5)->orderBy('number', 'ASC')->get();
        $info_ctr = TrainingInfo::where('section', 6)->orderBy('number', 'ASC')->get();

        $public_sections = PublicTrainingInfo::orderBy('order', 'ASC')->get();
        $public_sections_count = count(PublicTrainingInfo::get());
        $public_sections_order = [];
        $i = 0;
        foreach (range(0, $public_sections_count) as $p) {
            $public_sections_order[$i] = $p + 1;
            $i++;
        }
        $public_section_next = $i - 1;

        return view('dashboard.training.info')->with('info_minor_gnd', $info_minor_gnd)->with('info_minor_lcl', $info_minor_lcl)->with('info_minor_app', $info_minor_app)
            ->with('info_major_gnd', $info_major_gnd)->with('info_major_lcl', $info_major_lcl)->with('info_major_app', $info_major_app)
            ->with('info_ctr', $info_ctr)->with('public_sections', $public_sections)->with('public_section_order', $public_sections_order)
            ->with('public_section_next', $public_section_next);
    }

    public function addInfo(Request $request, $section) {
        $replacing = TrainingInfo::where('number', '>', $request->number)->where('section', $section)->get();
        if ($replacing != null) {
            foreach ($replacing as $r) {
                $new = $r->number + 1;
                $r->number = $new;
                $r->save();
            }
        }
        $info = new TrainingInfo;
        $info->number = $request->number + 1;
        $info->section = $request->section;
        $info->info = $request->info;
        $info->save();
        return redirect()->back()->with('success', 'The information has been added successfully.');
    }

    public function deleteInfo($id) {
        $info = TrainingInfo::find($id);
        $other_info = TrainingInfo::where('number', '>', $info->number)->get();
        foreach ($other_info as $o) {
            $o->number = $o->number - 1;
            $o->save();
        }
        $info->delete();
        return redirect()->back()->with('success', 'The information has been removed successfully.');
    }

    public function newPublicInfoSection(Request $request) {
        $request->validate([
            'name' => 'required',
            'order' => 'required'
        ]);

        if ($request->order < count(PublicTrainingInfo::get())) {
            $change_order = PublicTrainingInfo::where('order', '>=', $request->order)->get();
            foreach ($change_order as $c) {
                $c->order = $c->order + 1;
                $c->save();
            }
        }

        $info = new PublicTrainingInfo;
        $info->name = $request->name;
        $info->order = $request->order;
        $info->save();

        return redirect('/dashboard/training/info')->with('success', 'The section was added successfully.');
    }

    public function editPublicSection(Request $request, $id) {
        $request->validate([
            'name' => 'required'
        ]);

        $section = PublicTrainingInfo::find($id);
        $section->name = $request->name;
        $section->save();

        return redirect('/dashboard/training/info')->with('success', 'The section was updated successfully.');
    }

    public function saveSession() {
        $id = Auth::id();
        $nSessions = MentorAvail::where('trainee_id', $id)->where('slot', '>', Carbon::now())->count();



        $position = $request->input('position');
        $slot_id = $request->input('slot');
        $Slot = MentorAvail::find($slot_id);

        $Slot->trainee_id = $id;
        $Slot->position_id = $position;
        $Slot->trainee_comments = $request->input('comments');
        $Slot->save();

        ActivityLog::create(['note' => 'Accepted Session: ' . $Slot->slot, 'user_id' => Auth::id(), 'log_state' => 1, 'log_type' => 6]);

        $Slot->sendNewSessionEmail();
    }

    public function removePublicInfoSection($id) {
        $section = PublicTrainingInfo::find($id);
        $order = $section->order;
        $section->delete();

        $order_updates = PublicTrainingInfo::where('order', '>', $order)->get();
        foreach ($order_updates as $o) {
            $o->order = $o->order - 1;
            $o->save();
        }

        $pdfs = PublicTrainingInfoPdf::where('section_id', $id)->get();
        foreach ($pdfs as $p) {
            $p->delete();
        }

        return redirect('/dashboard/training/info')->with('success', 'The section was removed successfully.');
    }

    public function addPublicPdf(Request $request, $section_id) {
        $request->validate([
            'pdf' => 'required'
        ]);

        $ext = $request->file('pdf')->getClientOriginalExtension();
        $time = Carbon::now()->timestamp;
        $path = $request->file('pdf')->storeAs(
            'public/training_info',
            $time . '.' . $ext
        );
        $public_url = '/storage/training_info/' . $time . '.' . $ext;

        $pdf = new PublicTrainingInfoPdf;
        $pdf->section_id = $section_id;
        $pdf->pdf_path = $public_url;
        $pdf->save();

        return redirect('/dashboard/training/info')->with('success', 'The PDF was added successfully.');
    }

    public function removePublicPdf($id) {
        $pdf = PublicTrainingInfoPdf::find($id);
        $pdf->delete();

        return redirect('/dashboard/training/info')->with('success', 'The PDF was removed successfully.');
    }

    public function ticketsIndex(Request $request) {
        $controllers = User::where('status', '1')->orderBy('lname', 'ASC')->get()->filter(function ($user) {
            if (TrainingTicket::where('controller_id', $user->id)->first() != null || $user->visitor == 0) {
                return $user;
            }
        })->pluck('backwards_name', 'id');
        if ($request->id != null) {
            $search_result = User::find($request->id);
        } else {
            $search_result = null;
        }
        if ($search_result != null) {
            $tickets_sort = TrainingTicket::where('controller_id', $search_result->id)->get()->sortByDesc(function ($t) {
                return strtotime($t->date . ' ' . $t->start_time);
            })->pluck('id');
            $tickets_order = implode(',', array_fill(0, count($tickets_sort), '?'));
            $tickets = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->paginate(25);
            foreach ($tickets as &$t) {
                $t->position = $this->legacyTicketTypes($t->position);
                $t->sort_category = $this->getTicketSortCategory($t->position);
            }
            if ($tickets_sort->isEmpty() && ($search_result->status != 1)) { // Hide inactive users with no tickets from this view
                return redirect()->back()->with('error', 'There is no controller that exists with that CID.');
            }
            $exams = $this->getAcademyExamTranscript($request->id);
        } else {
            $tickets = null;
            $exams = null;
        }

        return view('dashboard.training.tickets')->with('controllers', $controllers)->with('search_result', $search_result)->with('tickets', $tickets)->with('exams', $exams);
    }

    public function getAcademyExamTranscript($cid) {
        $req_params = [
            'form_params' => [],
            'http_errors' => false
        ];
        $client = new Client();
        $res = $client->request('GET', 'https://api.vatusa.net/v2/academy/transcript/' . $cid . '?apikey=' . Config::get('vatusa.api_key'), $req_params);
        $academy = (string) $res->getBody();
        $exams = ['BASIC' => ['date' => null, 'success' => 3, 'grade' => null], 'S2' => ['date' => null, 'success' => 3, 'grade' => null], 'S3' => ['date' => null, 'success' => 3, 'grade' => null], 'C1' => ['date' => null, 'success' => 3, 'grade' => null]];
        $academy = json_decode($academy, true);
        $exam_names = array_keys($exams);
        foreach ($exam_names as $exam) {
            if (isset($academy['data'][$exam])) {
                foreach ($academy['data'][$exam] as $exam_attempt) {
                    if (is_null($exams[$exam]['date']) || ($exam_attempt['grade'] > $exams[$exam]['grade'])) {
                        $exams[$exam]['date'] = date("m/d/y", $exam_attempt['time_finished']);
                        $exams[$exam]['success'] = ($exam_attempt['grade'] >= 80) ? 1 : 0;
                        $exams[$exam]['grade'] = $exam_attempt['grade'];
                    }
                }
            }
        }
        return $exams;
    }

    public function searchTickets(Request $request) {
        $search_result = User::find($request->cid);
        if ($search_result != null) {
            return redirect('/dashboard/training/tickets?id=' . $search_result->id);
        } else {
            return redirect()->back()->with('error', 'There is no controller that exists with that CID.');
        }
    }

    public function newTrainingTicket(Request $request) {
        $c = $request->id;
        $controllers = User::where('status', '1')->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        return view('dashboard.training.new_ticket')->with('controllers', $controllers)->with('c', $c);
    }

    public function saveNewTicket(Request $request) {
        $request->validate([
            'controller' => 'required',
            'position' => 'required',
            'session_id' => 'required',
            'type' => 'required',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required',
            'duration' => 'required'
        ]);

        $ticket = new TrainingTicket;
        $ticket->controller_id = $request->controller;
        $ticket->trainer_id = Auth::id();
        $ticket->position = $request->position;
        $ticket->session_id = $request->session_id;
        $ticket->type = $request->type;
        $ticket->date = $request->date;
        $ticket->start_date = Carbon::createFromFormat('m/d/Y', $request->date)->toDateString();
        $ticket->start_time = $request->start;
        $ticket->end_time = $request->end;
        $ticket->duration = $request->duration;
        $ticket->comments = mb_convert_encoding($request->comments, 'UTF-8'); // character encoding added to protect save method
        $ticket->ins_comments = $request->trainer_comments;
        $ticket->cert = (is_null($request->cert)) ? 0 : $request->cert;
        $ticket->monitor = (is_null($request->monitor)) ? 0 : $request->monitor;
        $ticket->save();
        $extra = null;

        $date = $ticket->date;
        $date = date("Y-m-d");
        $controller = User::find($ticket->controller_id);
        $trainer = User::find($ticket->trainer_id);

        try {
            Mail::send(['html' => 'emails.training_ticket'], ['ticket' => $ticket, 'controller' => $controller, 'trainer' => $trainer], function ($m) use ($controller, $ticket) {
                $m->from('training@notams.ztlartcc.org', 'vZTL ARTCC Training Department');
                $m->subject('New Training Ticket Submitted');
                $m->to($controller->email)->cc('training@ztlartcc.org');
            });
        } catch (Exception $e) {
            Log::info('Unable to send training ticket email: ' . $e);
        }

        // Position type must match regex /^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$/ to be accepted by VATUSA
        if ($request->position == 113 || $request->position == 112) {
            $ticket->position = 'ATL_TWR';
        } elseif ($request->position == 100 || $request->position == 101) {
            $ticket->position = 'ZTL_DEL';
        } elseif ($request->position == 103 || $request->position == 104) {
            $ticket->position = 'ATL_DEL';
        } elseif ($request->position == 102) {
            $ticket->position = 'CLT_DEL';
        } elseif ($request->position == 105) {
            $ticket->position = 'ZTL_GND';
        } elseif ($request->position == 106) {
            $ticket->position = 'CLT_GND';
        } elseif ($request->position == 107 || $request->position == 108) {
            $ticket->position = 'ATL_GND';
        } elseif ($request->position == 109 || $request->position == 110) {
            $ticket->position = 'ZTL_TWR';
        } elseif ($request->position == 111) {
            $ticket->position = 'CLT_TWR';
        } elseif ($request->position == 114 || $request->position == 115) {
            $ticket->position = 'ZTL_APP';
        } elseif ($request->position == 116) {
            $ticket->position = 'CLT_APP';
        } elseif ($request->position == 117 || $request->position == 118 || $request->position == 119) {
            $ticket->position = 'ATL_APP';
        } elseif ($request->position == 120 || $request->position == 121) {
            $ticket->position = 'ATL_CTR';
        } elseif ($request->position == 122) {
            $ticket->position = 'ZTL_RCR';
        } elseif ($request->position == 123) {
            $ticket->position = 'BHM_APP';
        }

        $req_params = [
            'form_params' =>
            [
                'instructor_id' => Auth::id(),
                'session_date' => $date . ' ' . $request->start,
                'position' => $ticket->position,
                'duration' => $request->duration,
                'notes' => $request->comments,
                'location' => 1
            ],
            'http_errors' => false
        ];

        $client = new Client();
        $res = $client->request('POST', 'https://api.vatusa.net/v2/user/' . $request->controller . '/training/record?apikey=' . Config::get('vatusa.api_key'), $req_params);

        if ($request->ots == 1) {
            $ots = new Ots;
            $ots->controller_id = $ticket->controller_id;
            $ots->recommender_id = $ticket->trainer_id;
            $ots->position = $request->position;
            $ots->status = 0;
            $ots->save();
            $extra = ' and the OTS recommendation has been added';
        }
        if ($request->monitor == 1) {
            if ($request->position == 10) {
                $controller->del = 88;
            } elseif ($request->position == 13) {
                $controller->del == 89;
            } elseif ($request->position == 17) {
                $controller->gnd = 88;
            } elseif ($request->position == 21) {
                $controller->gnd = 89;
            } elseif ($request->position == 26) {
                $controller->twr = 88;
            } elseif ($request->position == 30) {
                $controller->twr = 89;
            } elseif ($request->position == 35) {
                $controller->app = 88;
            } elseif ($request->position == 41) {
                $controller->app = 89;
            } elseif ($request->position == 47) {
                $controller->gnd = 89;
            }
            $controller->save();
        }

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name . ' added a training ticket for ' . User::find($ticket->controller_id)->full_name . '.';
        $audit->save();

        return redirect('/dashboard/training/tickets?id=' . $ticket->controller_id)->with('success', 'The training ticket has been submitted successfully' . $extra . '.');
    }

    public function viewTicket($id) {
        $ticket = TrainingTicket::find($id);
        $ticket->position = $this->legacyTicketTypes($ticket->position);
        return view('dashboard.training.view_ticket')->with('ticket', $ticket);
    }

    public function editTicket($id) {
        $ticket = TrainingTicket::find($id);
        $ticket->position = $this->legacyTicketTypes($ticket->position);
        if (Auth::id() == $ticket->trainer_id || Auth::user()->isAbleTo('snrStaff')) {
            $controllers = User::where('status', '1')->where('canTrain', '1')->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
            return view('dashboard.training.edit_ticket')->with('ticket', $ticket)->with('controllers', $controllers);
        } else {
            return redirect()->back()->with('error', 'You can only edit tickets that you have submitted unless you are the TA.');
        }
    }

    public function saveTicket(Request $request, $id) {
        $ticket = TrainingTicket::find($id);
        if (Auth::id() == $ticket->trainer_id || Auth::user()->isAbleTo('snrStaff')) {
            $request->validate([
                'controller' => 'required',
                'position' => 'required',
                'session_id' => 'required',
                'type' => 'required',
                'date' => 'required',
                'start' => 'required',
                'end' => 'required',
                'duration' => 'required'
            ]);

            $ticket->controller_id = $request->controller;
            $ticket->position = $request->position;
            $ticket->session_id = $request->session_id;
            $ticket->type = $request->type;
            $ticket->date = $request->date;
            $ticket->start_date = Carbon::createFromFormat('m/d/Y', $request->date)->toDateString();
            $ticket->start_time = $request->start;
            $ticket->end_time = $request->end;
            $ticket->duration = $request->duration;
            $ticket->comments = $request->comments;
            $ticket->ins_comments = $request->trainer_comments;
            $ticket->cert = (is_null($request->cert)) ? 0 : $request->cert;
            $ticket->monitor = (is_null($request->monitor)) ? 0 : $request->monitor;
            $ticket->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name . ' edited a training ticket for ' . User::find($request->controller)->full_name . '.';
            $audit->save();

            return redirect('/dashboard/training/tickets/view/' . $ticket->id)->with('success', 'The ticket has been updated successfully.');
        } else {
            return redirect()->back()->with('error', 'You can only edit tickets that you have submitted unless you are the TA.');
        }
    }

    public function deleteTicket($id) {
        $ticket = TrainingTicket::find($id);
        if (Auth::user()->isAbleTo('snrStaff')) {
            $controller_id = $ticket->controller_id;
            $ticket->delete();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name . ' deleted a training ticket for ' . User::find($controller_id)->full_name . '.';
            $audit->save();

            return redirect('/dashboard/training/tickets?id=' . $controller_id)->with('success', 'The ticket has been deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Only the TA can delete training tickets.');
        }
    }

    public function otsCenter() {
        $ots_new = Ots::where('status', 0)->orderBy('created_at', 'DESC')->paginate(25);
        $ots_accepted = Ots::where('status', 1)->orderBy('created_at', 'DESC')->paginate(25);
        $ots_complete = Ots::where('status', 2)->orWhere('status', 3)->orderBy('created_at', 'DESC')->paginate(25);
        $instructors = User::orderBy('lname', 'ASC')->get()->filter(function ($user) {
            return $user->hasRole('ins');
        })->pluck('full_name', 'id');
        return view('dashboard.training.ots-center')->with('ots_new', $ots_new)->with('ots_accepted', $ots_accepted)->with('ots_complete', $ots_complete)->with('instructors', $instructors);
    }

    public function acceptRecommendation($id) {
        $ots = Ots::find($id);
        $ots->status = 1;
        $ots->ins_id = Auth::id();
        $ots->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name . ' accepted an OTS for ' . User::find($ots->controller_id)->full_name . '.';
        $audit->save();

        return redirect()->back()->with('success', 'You have sucessfully accepted this OTS. Please email the controller at ' . User::find($ots->controller_id)->email . ' in order to schedule the OTS.');
    }

    public function rejectRecommendation($id) {
        if (!Auth::user()->isAbleTo('snrStaff')) {
            return redirect()->back()->with('error', 'Only the TA can reject OTS recommendations.');
        } else {
            $ots = Ots::find($id);
            $ots->delete();

            return redirect()->back()->with('success', 'The OTS recommendation has been rejected successfully.');
        }
    }

    public function assignRecommendation(Request $request, $id) {
        if (!Auth::user()->isAbleTo('snrStaff')) {
            return redirect()->back()->with('error', 'Only the TA can assign OTS recommendations to instructors.');
        } else {
            $ots = Ots::find($id);
            $ots->status = 1;
            $ots->ins_id = $request->ins;
            $ots->save();

            $ins = User::find($ots->ins_id);
            $controller = User::find($ots->controller_id);

            Mail::send('emails.ots_assignment', ['ots' => $ots, 'controller' => $controller, 'ins' => $ins], function ($m) use ($ins, $controller) {
                $m->from('ots-center@notams.ztlartcc.org', 'vZTL ARTCC OTS Center')->replyTo($controller->email, $controller->full_name);
                $m->subject('You Have Been Assigned an OTS for ' . $controller->full_name);
                $m->to($ins->email)->cc('training@ztlartcc.org');
            });

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name . ' assigned an OTS for ' . User::find($ots->controller_id)->full_name . ' to ' . User::find($ots->ins_id)->full_name . '.';
            $audit->save();

            return redirect()->back()->with('success', 'The OTS has been assigned successfully and the instructor has been notified.');
        }
    }

    public function completeOTS(Request $request, $id) {
        $validator = $request->validate([
            'result' => 'required',
            'ots_report' => 'required'
        ]);

        $ots = Ots::find($id);

        if ($ots->ins_id == Auth::id() || Auth::user()->isAbleTo('snrStaff')) {
            $ext = $request->file('ots_report')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('ots_report')->storeAs(
                'public/ots_reports',
                $time . '.' . $ext
            );
            $public_url = '/storage/ots_reports/' . $time . '.' . $ext;

            $ots->status = $request->result;
            $ots->report = $public_url;
            $ots->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name . ' updated an OTS for ' . User::find($ots->controller_id)->full_name . '.';
            $audit->save();

            return redirect()->back()->with('success', 'The OTS has been updated successfully!');
        } else {
            return redirect()->back()->with('error', 'This OTS has not been assigned to you.');
        }
    }

    public function otsCancel($id) {
        $ots = Ots::find($id);
        $ots->ins_id = null;
        $ots->status = 0;
        $ots->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name . ' cancelled an OTS for ' . User::find($ots->controller_id)->full_name . '.';
        $audit->save();

        return redirect()->back()->with('success', 'The OTS has been unassigned from you and cancelled successfully.');
    }

    public function getTicketSortCategory($position) { // Takes a position id and returns the sort category (ex. S1, S2, S3, C1, Other)
        switch (true) {
            case ($position > 6 && $position < 22):
                return 's1';
                break;
            case ($position > 99 && $position < 103):
                return 's1';
                break;
            case ($position > 104 && $position < 107):
                return 's1';
                break;
            case ($position > 21 && $position < 31):
                return 's2';
                break;
            case ($position > 102 && $position < 105):
                return 's2';
                break;
            case ($position > 106 && $position < 114):
                return 's2';
                break;
            case ($position > 30 && $position < 42):
                return 's3';
                break;
            case ($position > 113 && $position < 120):
                return 's3';
                break;
            case ($position == 123):
                return 's3';
                break;
            case ($position > 41 && $position < 48):
                return 'c1';
                break;
            case ($position > 119 && $position < 122):
                return 'c1';
                break;
            case ($position > 121 && $position != 123):
                return 'other';
                break;
            default:
                return 'other';
        }
    }

    public function legacyTicketTypes($position) { // Returns modern ticket ids for legacy ticket types
        switch ($position) {
            case 11:
                return 104;
                break;
            case 103:
                return 104;
                break;
            case 18:
                return 108;
                break;
            case 107:
                return 108;
                break;
            case 27:
                return 113;
                break;
            case 112:
                return 113;
                break;
            case 31:
                return 115;
                break;
            case 32:
                return 115;
                break;
            case 114:
                return 115;
                break;
            case 42:
                return 121;
                break;
            case 120:
                return 121;
                break;
            default:
                return $position;
        }
    }

    public function statistics(Request $request) {
        $yearSel = $monthSel = null;
        if (isset($request->date_select)) {
            $datePart = explode(' ', $request->date_select); // Format: MM YYYY
            $monthSel = $datePart[0];
            $yearSel = $datePart[1];
        }
        $stats = $this->generateTrainingStats($yearSel, $monthSel, 'stats');
        return view('dashboard.training.statistics')->with('stats', $stats);
    }

    private function generateTrainingStats($year, $month, $dataType) {
        $retArr = [];
        $retArr['dateSelect'] = ['month' => $month, 'year' => $year];
        // Set date range
        if ($year == null) {
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
        }
        $from = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $to = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $retArr['date'] = ['start_date' => $from, 'end_date' => $to];
        $sessions = TrainingTicket::whereBetween('start_date', [$from, $to])->get();
        if ($dataType == 'stats') {
            $sessionsPrevious = TrainingTicket::whereBetween('start_date', [Carbon::createFromDate($from)->startOfMonth()->subMonths(1)->toDateString(), Carbon::createFromDate($to)->startOfMonth()->subMonths(1)->toDateString()]);
        }
        // Training sessions per month
        $retArr['sessionsPerMonth'] = $sessions->count();
        if ($dataType == 'stats') {
            $retArr['sessionsCompletePerMonth'] = $sessions->where('type', 12)->count();
            $retArr['sessionsPerMonthNoShow'] = $sessions->where('type', 10)->count();
            $retArr['sessionsPreviousMonth'] = $sessionsPrevious->count();
            $retArr['sessionsCompletePreviousMonth'] = $sessionsPrevious->where('type', 12)->count();
        }
        // Training sessions per month by type
        if ($dataType == 'graph') {
            $sessionsS1 = $sessions->where('position', '<', 105)->count();
            $sessionsS2 = $sessions->where('position', '>', 105)->where('position', '<', 115)->count();
            $sessionsS3 = $sessions->whereIn('position', [115, 116, 117, 118, 119, 123])->count();
            $sessionsC1 = $sessions->whereIn('position', [120, 121])->count();
            $sessionsOther = $sessions->whereIn('position', [122, 124, 125])->count();
            $retArr['sessionsByType'] = ['S1' => $sessionsS1, 'S2' => $sessionsS2, 'S3' => $sessionsS3, 'C1' => $sessionsC1, 'Other' => $sessionsOther];
        }
        // Training sessions, type, and hours by instructor
        $trainers = $trainerSessions = [];
        $activeRoster = User::where('status', '1')->where('visitor', '0')->get()->unique('id');
        foreach ($activeRoster as $activeUser) {
            if ($activeUser->getTrainPositionAttribute() > 0) {
                $trainers[] = $activeUser;
            }
        }
        $trainingStaffBelowMins = 0;
        foreach ($trainers as $trainer) {
            $trainerStats = [];
            $trainerStats['name'] = explode(' ', $trainer->getFullNameAttribute())[1];
            $trainerSesh = $sessions->where('trainer_id', $trainer->id);
            $trainerStats['total'] = $trainerSesh->count();
            $trainerStats['S1'] = $trainerSesh->where('position', '<', 105)->count();
            $trainerStats['S2'] = $trainerSesh->where('position', '>', 105)->where('position', '<', 115)->count();
            $trainerStats['S3'] = $trainerSesh->where('position', '>', 115)->where('position', '<', 120)->count();
            $trainerStats['S3'] += $trainerSesh->where('position', 123)->count();
            $trainerStats['C1'] = $trainerSesh->where('position', 120)->count();
            $trainerStats['C1'] += $trainerSesh->where('position', 121)->count();
            $trainerStats['Other'] = $trainerStats['total'] - $trainerStats['S1'] - $trainerStats['S2'] - $trainerStats['S3'] - $trainerStats['C1'];
            if ($trainerStats['total'] < Config::get('ztl.trainer_min_sessions')) {
                $trainingStaffBelowMins++;
            }
            $trainerSessions[] = $trainerStats;
        }
        $retArr['trainerSessions'] = $trainerSessions;
        // Students requiring training
        if ($dataType == 'graph') {
            $students = User::where('status', '1')->where('visitor', '0')->where('canTrain', '1')->where('rating_id', '<', '5')->get();
            $studentsS1 = $students->where('rating_id', '1')->count();
            $studentsS2 = $students->where('rating_id', '2')->count();
            $studentsS3 = $students->where('rating_id', '3')->count();
            $studentsC1 = $students->where('rating_id', '4')->count();
            $retArr['studentsRequireTng'] = ['S1' => $studentsS1, 'S2' => $studentsS2, 'S3' => $studentsS3, 'C1' => $studentsC1];
        }
        // Number of unique students
        if ($dataType == 'stats') {
            $uniqueStudents = $sessions->unique('controller_id')->count();
            $retArr['uniqueStudents'] = $uniqueStudents;
        }
        // Number of OTS' per month
        if ($dataType == 'stats') {
            $otsMonth = Ots::whereBetween('updated_at', [$from . ' 00:00:00', $to . ' 00:00:00'])->get();
            $otsPerMonthPass = $otsMonth->where('status', '2')->count();
            $otsPerMonthFail = $otsMonth->where('status', '3')->count();
            $retArr['otsPerMonth'] = ['pass' => $otsPerMonthPass, 'fail' => $otsPerMonthFail];
        }
        // Average session duration (over last 6-months)
        if ($dataType == 'graph') {
            $sessionsSixMonths = TrainingTicket::where('start_date', '>', Carbon::now()->subMonths(6))->get();
            $uniqueSessionIDs = $sessionsSixMonths->unique('session_id');
            $sessionDuration = [];
            foreach ($uniqueSessionIDs as $uniqueSessionID) {
                $seshByTypeDuration = $averageSeshDuration = null;
                $seshByType = $sessionsSixMonths->where('session_id', $uniqueSessionID->session_id);
                foreach ($seshByType as $sesh) {
                    $seshByTypeDuration += strtotime("1970-01-01 " . $sesh->duration . ":00");
                }
                $averageSeshDuration = $seshByTypeDuration / $seshByType->count() / 60; // Get duration in minutes
                $sessionDuration[] = [$uniqueSessionID->getSessionNameAttribute(), round($averageSeshDuration, 2)];
            }
            $retArr['sessionDuration'] = $sessionDuration;
        }
        // Number of student cancellations, no shows
        if ($dataType == 'stats') {
            $setmoreAccessToken = null;
            $setmoreAppointments = [];
            $studentCancel = $studentNoShow = 0;
            $client = new Client();
            try {
                $res = $client->request('GET', Config::get('setmore.endpoint') . '/o/oauth2/token?refreshToken=' . Config::get('setmore.api_key'));
                $setmoreAuthResp = (string) $res->getBody();
                $setmoreAuthRespA = json_decode($setmoreAuthResp, true);
            } catch (ClientErrorResponseException $exception) {
                // Unused - don't throw an error
            }
            if (isset($setmoreAuthRespA) && isset($setmoreAuthRespA['response'])) {
                if ($setmoreAuthRespA['response'] && isset($setmoreAuthRespA['data']['token'])) {
                    $setmoreAccessToken = $setmoreAuthRespA['data']['token']['access_token'];
                }
            }
            if ($setmoreAccessToken != null) {
                $setmoreAppointments = $this->getSetmoreAppointments($setmoreAccessToken, $from, $to);
                foreach ($setmoreAppointments as $setmoreAppointment) {
                    if (isset($setmoreAppointment['label'])) {
                        if ($setmoreAppointment['label'] == 'Cancel') {
                            $studentCancel++;
                        } elseif ($setmoreAppointment['label'] == 'No-Show') {
                            $studentNoShow++;
                        }
                    }
                }
            }
            $retArr['studentCancel'] = $studentCancel;
            $retArr['studentNoShow'] = $studentNoShow;
        }
        // Generate TA's monthly report
        if ($dataType == 'stats') {
            if ($retArr['sessionsPerMonth'] == 0) {
                $percentSessionsChange = '-100';
            } elseif ($retArr['sessionsPreviousMonth'] == 0) {
                $percentSessionsChange = '+100';
            } else {
                $percentSessionsChange = round($retArr['sessionsPerMonth'] / $retArr['sessionsPreviousMonth'], 1);
                if ($percentSessionsChange > 0) {
                    $percentSessionsChange = '+' . $percentSessionsChange;
                }
            }
            if ($retArr['sessionsCompletePerMonth'] == 0) {
                $percentSessionsCompleteChange = '-100';
            } elseif ($retArr['sessionsCompletePreviousMonth'] == 0) {
                $percentSessionsCompleteChange = '+100';
            } else {
                $percentSessionsCompleteChange = round($retArr['sessionsCompletePerMonth'] / $retArr['sessionsCompletePreviousMonth'], 1);
                if ($percentSessionsCompleteChange > 0) {
                    $percentSessionsCompleteChange = '+' . $percentSessionsCompleteChange;
                }
            }
            $retArr['taMonthlyReport'] = "In the Month of " . Carbon::createFromDate($retArr['date']['start_date'])->format('F') . ", ZTL has offered " . $retArr['sessionsPerMonth'] . " training sessions (" . $percentSessionsChange . "% change from " . Carbon::createFromDate($retArr['date']['start_date'])->subMonths(1)->format('F') . "). " . $retArr['sessionsCompletePerMonth'] . " sessions were completed (" . $percentSessionsCompleteChange . "%), with " . $retArr['sessionsPerMonthNoShow'] . " known no-shows. " . $trainingStaffBelowMins . " Training Staff members did not meet monthly minimums.";
        }
        return $retArr;
    }

    private function getSetmoreAppointments($setmoreAccessToken, $from, $to) {
        $setmoreAppointments = [];
        $setmoreCursor = null;
        do {
            $appointmentBatch = $this->getAppointmentBatch($setmoreAccessToken, $from, $to, $setmoreCursor);
            if (is_array($appointmentBatch)) {
                $setmoreAppointments = array_merge($setmoreAppointments, $appointmentBatch);
            }
        } while (is_numeric($setmoreCursor));
        return $setmoreAppointments;
    }

    private function getAppointmentBatch($setmoreAccessToken, $from, $to, &$cursor) {
        $cursorStr = '';
        if (!is_null($cursor)) {
            $cursorStr = '&cursor=' . $cursor;
        }
        $fromDate = Carbon::createFromDate($from)->format('d-m-Y');
        $toDate = Carbon::createFromDate($to)->format('d-m-Y');
        $client = new Client();
        $res = $client->request(
            'GET',
            Config::get('setmore.endpoint') . '/bookingapi/appointments?startDate=' . $fromDate . '&endDate=' . $toDate . $cursorStr,
            [
                'headers' =>
                [
                    'Content-type' => "application/json",
                    'Authorization' => "Bearer {$setmoreAccessToken}"
                ]
            ]
        );
        $setmoreResp = (string) $res->getBody();
        $setmoreRespA = json_decode($setmoreResp, true);
        if (isset($setmoreRespA['data']['cursor'])) {
            $cursor = $setmoreRespA['data']['cursor'];
        } else {
            $cursor = null;
        }
        if (isset($setmoreRespA['data']['appointments'])) {
            return $setmoreRespA['data']['appointments'];
        }
        return null;
    }

    public function generateGraphs(Request $request) {
        $graphId = $request->id;
        $statArr = TrainingDash::generateTrainingStats($request->year, $request->month, 'graph');
        // Reformat date range
        $from = Carbon::createFromDate($statArr['date']['start_date'])->format('m/j/Y');
        $to = Carbon::createFromDate($statArr['date']['end_date'])->format('m/j/Y');
        MtJpGraph::load(['bar', 'plotline']);
        $graph = new \Graph(600, 600);
        $graph->SetFrame(false, 'black', 0);
        $graph->SetScale("textlin");
        // Graph configuration
        $statsGraphs = [
            1 => ['title' => 'Sessions Per Month', 'subtitle' => '(' . $from . ' - ' . $to . ')', 'x-title' => 'Session Type', 'y-title' => 'Number of Sessions', 'dataset' => 'sessionsByType'],
            2 => ['title' => 'Sessions Per Month By Instructor/Mentor', 'subtitle' => '(' . $from . ' - ' . $to . ')', 'x-title' => '', 'y-title' => 'Number of Sessions', 'dataset' => null],
            3 => ['title' => 'Average Session Duration', 'subtitle' => 'Last Six Months', 'x-title' => '', 'y-title' => 'Average Session Duration (minutes)', 'dataset' => 'sessionDuration'],
            4 => ['title' => 'Students Requiring Training', 'subtitle' => 'As of ' . Carbon::now()->format('m/d/Y'), 'x-title' => 'Student Type', 'y-title' => 'Number of Students', 'dataset' => 'studentsRequireTng']
        ];
        $graph->title->Set($statsGraphs[$graphId]['title']);
        $graph->subtitle->Set($statsGraphs[$graphId]['subtitle']);
        $graph->xaxis->SetTitle($statsGraphs[$graphId]['x-title'], 'center');
        $graph->yaxis->SetTitle($statsGraphs[$graphId]['y-title'], 'middle');
        $graph->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);

        $noData = new \Text('No Data Available');
        $noData->SetPos(0.5, 0.5, 'center', 'center');
        $noData->SetParagraphAlign('center');
        $noData->SetColor('black');
        $noData->SetFont(FF_FONT1, FS_BOLD);
        $noData->SetBox();

        if (in_array($graphId, [TrainingDash::$GRAPH_SESSIONS_PER_MONTH, TrainingDash::$GRAPH_STUDENT_TRAINING_PER_RATING])) {
            $bplot = new \BarPlot(array_values($statArr[$statsGraphs[$graphId]['dataset']]));
            $graph->Add($bplot);
            $graph->xaxis->SetTickLabels(array_keys($statArr[$statsGraphs[$graphId]['dataset']]));
        } elseif ($graphId == TrainingDash::$GRAPH_SESSIONS_BY_INSTRUCTOR) {
            if (count($statArr['trainerSessions']) == 0) {
                $statArr['trainerSessions'][] = ['name'=>'','S1'=>0,'S2'=>0,'S3'=>0,'C1'=>0,'Other'=>0,'total'=>0];
                $graph->AddText($noData);
            }
            $instructors = $plotArray = [];
            $instructionalCategories = ['S1', 'S2', 'S3', 'C1', 'Other'];
            foreach ($statArr['trainerSessions'] as $instructor) {
                $instructors[] = $instructor['name'];
                foreach ($instructionalCategories as $instructionalCategory) {
                    $$instructionalCategory[] = $instructor[$instructionalCategory];
                }
            }
            foreach ($instructionalCategories as $instructionalCategory) {
                $plotArray[] = new \BarPlot($$instructionalCategory);
                end($plotArray)->SetLegend($instructionalCategory);
            }
            $gbPlot = new \AccBarPlot($plotArray);
            $graph->Add($gbPlot);
            $line = new \PlotLine(HORIZONTAL, Config::get('ztl.trainer_min_sessions'), 'red', 1);
            $graph->AddLine($line);
            $graph->legend->SetColumns(5);
            $graph->legend->Pos(0.5, 0.1, "center", "top");
            $graph->xaxis->SetTickLabels($instructors);
            $graph->xaxis->SetLabelAngle(50);
        } elseif ($graphId == TrainingDash::$GRAPH_SESSION_AVERAGE_DURATION) {
            if (count($statArr['sessionDuration']) == 0) {
                $statArr['sessionDuration'][] = ['', 0];
                $graph->AddText($noData);
            }
            // Create the bar plots
            $sessionAvgTime = $sessionId = [];
            foreach ($statArr['sessionDuration'] as $seshType) {
                $sIdExp = explode(' ', $seshType[0]);
                if ($sIdExp[0] == 'Unlisted/other') {
                    $sessionId[] = 'Other';
                } else {
                    $sessionId[] = $sIdExp[0];
                }
                $sessionAvgTime[] = $seshType[1];
            }
            array_multisort($sessionId, $sessionAvgTime);
            $bPlot = new \BarPlot($sessionAvgTime);
            $graph->Add($bPlot);
            $graph->xaxis->SetTickLabels($sessionId);
            $graph->xaxis->SetLabelAngle(50);
        }

        $response = Response::make($graph->Stroke());
        $response->header('Content-type', 'image/png');
        return $response;
    }
}
