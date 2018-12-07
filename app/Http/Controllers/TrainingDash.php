<?php

namespace App\Http\Controllers;

use App\Ots;
use App\TrainingInfo;
use App\TrainingTicket;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

class TrainingDash extends Controller
{
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
        return view('dashboard.training.info')->with('info_minor_gnd', $info_minor_gnd)->with('info_minor_lcl', $info_minor_lcl)->with('info_minor_app', $info_minor_app)
                                              ->with('info_major_gnd', $info_major_gnd)->with('info_major_lcl', $info_major_lcl)->with('info_major_app', $info_major_app)
                                              ->with('info_ctr', $info_ctr);
    }

    public function addInfo(Request $request, $section) {
        $replacing = TrainingInfo::where('number', '>', $request->number)->where('section', $section)->get();
        if($replacing != null) {
            foreach($replacing as $r) {
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
        $info->delete();
        return redirect()->back()->with('success', 'The information has been removed successfully.');
    }

    public function ticketsIndex(Request $request) {
        $controllers = User::where('visitor', '0')->where('status', '1')->where('canTrain', '1')->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        if($request->id != null) {
            $search_result = User::find($request->id);
        } else {
            $search_result = null;
        }
        if($search_result != null) {
            $tickets = TrainingTicket::where('controller_id', $search_result->id)->orderBy('date', 'DSC')->orderBy('start_time', 'DSC')->paginate(25);
        } else {
            $tickets = null;
        }

        return view('dashboard.training.tickets')->with('controllers', $controllers)->with('search_result', $search_result)->with('tickets', $tickets);
    }

    public function searchTickets(Request $request) {
        $search_result = User::find($request->cid);
        if($search_result != null) {
            return redirect('/dashboard/training/tickets?id='.$search_result->id);
        } else {
            return redirect()->back()->with('error', 'There is not controlling that exists with this CID.');
        }
    }

    public function newTrainingTicket(Request $request) {
        $c = $request->id;
        $controllers = User::where('status', '1')->where('canTrain', '1')->orderBy('lname', 'ASC')->get()->pluck('full_name', 'id');
        return view('dashboard.training.new_ticket')->with('controllers', $controllers)->with('c', $c);
    }

    public function saveNewTicket(Request $request) {
        $request->validate([
            'controller' => 'required',
            'position' => 'required',
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
        $ticket->type = $request->type;
        $ticket->date = $request->date;
        $ticket->start_time = $request->start;
        $ticket->end_time = $request->end;
        $ticket->duration = $request->duration;
        $ticket->comments = $request->comments;
        $ticket->ins_comments = $request->trainer_comments;
        $ticket->save();
        $extra = null;

        $controller = User::find($ticket->controller_id);
        $trainer = User::find($ticket->trainer_id);

        if($request->ots == 1) {
            $ots = new Ots;
            $ots->controller_id = $ticket->controller_id;
            $ots->recommender_id = $ticket->trainer_id;
            $ots->position = $ticket->position;
            $ots->status = 0;
            $ots->save();
            $extra = ' and the OTS recommendation has been added';
        }

        Mail::send(['html' => 'emails.training_ticket'], ['ticket' => $ticket, 'controller' => $controller, 'trainer' => $trainer], function ($m) use ($controller, $ticket) {
            $m->from('training@notams.ztlartcc.org', 'vZTL ARTCC Training Department');
            $m->subject('New Training Ticket Submitted');
            $m->to($controller->email)->cc('ta@ztlartcc.org');
        });

        return redirect('/dashboard/training/tickets?id='.$ticket->controller_id)->with('success', 'The training ticket has been submitted successfully'.$extra.'.');
    }

    public function viewTicket($id) {
        $ticket = TrainingTicket::find($id);
        return view('dashboard.training.view_ticket')->with('ticket', $ticket);
    }

    public function editTicket($id) {
        $ticket = TrainingTicket::find($id);
        if(Auth::id() == $ticket->trainer_id || Auth::user()->can('snrStaff')) {
            $controllers = User::where('visitor', '0')->where('status', '1')->where('canTrain', '1')->orderBy('lname', 'ASC')->get()->pluck('full_name', 'id');
            return view('dashboard.training.edit_ticket')->with('ticket', $ticket)->with('controllers', $controllers);
        } else {
            return redirect()->back()->with('error', 'You can only edit tickets that you have submitted unless you are the TA.');
        }
    }

    public function saveTicket(Request $request, $id) {
        $ticket = TrainingTicket::find($id);
        if(Auth::id() == $ticket->trainer_id || Auth::user()->can('snrStaff')) {
            $request->validate([
                'controller' => 'required',
                'position' => 'required',
                'type' => 'required',
                'date' => 'required',
                'start' => 'required',
                'end' => 'required',
                'duration' => 'required'
            ]);

            $ticket->controller_id = $request->controller;
            $ticket->position = $request->position;
            $ticket->type = $request->type;
            $ticket->date = $request->date;
            $ticket->start_time = $request->start;
            $ticket->end_time = $request->end;
            $ticket->duration = $request->duration;
            $ticket->comments = $request->comments;
            $ticket->ins_comments = $request->trainer_comments;
            $ticket->save();

            return redirect('/dashboard/training/tickets/view/'.$ticket->id)->with('success', 'The ticket has been updated successfully.');
        } else {
            return redirect()->back()->with('error', 'You can only edit tickets that you have submitted unless you are the TA.');
        }
    }

    public function deleteTicket($id) {
        $ticket = TrainingTicket::find($id);
        if(Auth::user()->can('snrStaff')) {
            $controller_id = $ticket->controller_id;
            $ticket->delete();
            return redirect('/dashboard/training/tickets?id='.$controller_id)->with('success', 'The ticket has been deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Only the TA can delete training tickets.');
        }
    }

    public function otsCenter() {
        $ots_new = Ots::where('status', 0)->orderBy('created_at', 'DSC')->paginate(25);
        $ots_accepted = Ots::where('status', 1)->orderBy('created_at', 'DSC')->paginate(25);
        $ots_complete = Ots::where('status', 2)->orWhere('status', 3)->orderBy('created_at', 'DSC')->paginate(25);
        $instructors = User::orderBy('lname', 'ASC')->get()->filter(function($user){
                    return $user->hasRole('ins');
                })->pluck('full_name', 'id');
        return view('dashboard.training.ots-center')->with('ots_new', $ots_new)->with('ots_accepted', $ots_accepted)->with('ots_complete', $ots_complete)->with('instructors', $instructors);
    }

    public function acceptRecommendation($id) {
        $ots = Ots::find($id);
        $ots->status = 1;
        $ots->ins_id = Auth::id();
        $ots->save();
        return redirect()->back()->with('success', 'You have sucessfully accepted this OTS. Please email the controller at '.User::find($ots->controller_id)->email.' in order to schedule the OTS.');
    }

    public function rejectRecommendation($id) {
        if(!Auth::user()->can('snrStaff')) {
            return redirect()->back()->with('error', 'Only the TA can reject OTS recommendations.');
        } else {
            $ots = Ots::find($id);
            $ots->delete();;
            return redirect()->back()->with('success', 'The OTS recommendation has been rejected successfully.');
        }
    }

    public function assignRecommendation(Request $request, $id) {
        if(!Auth::user()->can('snrStaff')) {
            return redirect()->back()->with('error', 'Only the TA can assign OTS recommendations to instructors.');
        } else {
            $ots = Ots::find($id);
            $ots->status = 1;
            $ots->ins_id = $request->ins;
            $ots->save();
            return redirect()->back()->with('success', 'The OTS has been assigned successfully.');
        }
    }

    public function completeOTS(Request $request, $id) {
        $validator = $request->validate([
            'result' => 'required',
            'ots_report' => 'required'
        ]);

        $ots = Ots::find($id);

        if($ots->ins_id == Auth::id()) {
            $ext = $request->file('ots_report')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('ots_report')->storeAs(
                'public/ots_reports', $time.'.'.$ext
            );
            $public_url = '/storage/ots_reports/'.$time.'.'.$ext;

            $ots->status = $request->result;
            $ots->report = $public_url;
            $ots->save();

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

        return redirect()->back()->with('success', 'The OTS has been unassigned from you and cancelled successfully.');
    }
}
