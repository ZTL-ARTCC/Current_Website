<?php

namespace App\Http\Controllers;
use App\MentorAvai;
use App\Audit;
use App\Ots;
use App\PublicTrainingInfo;
use App\PublicTrainingInfoPdf;
use App\TrainingInfo;
use App\TrainingTicket;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use GuzzleHttp\Client;
use Config;

class TrainingDash extends Controller
{
    public function showATCast() {
        return view('dashboard.training.atcast');
    }

    public function moodleLogin() {
        Auth::user()->enrolInMoodleCourses();
        return view('dashboard.training.moodle');
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
        $public_sections_order = array();
        $i = 0;
        foreach(range(0, $public_sections_count) as $p) {
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
        $other_info = TrainingInfo::where('number', '>', $info->number)->get();
        foreach($other_info as $o) {
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

        if($request->order < count(PublicTrainingInfo::get())) {
            $change_order = PublicTrainingInfo::where('order', '>=', $request->order)->get();
            foreach($change_order as $c) {
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
   
    public function saveSession()
	{
		$id = Auth::id();
		$nSessions = MentorAvail::where('trainee_id', $id)->where('slot', '>', Carbon::now())->count();

		

		$position = Input::get('position');
		$slot_id = Input::get('slot');
		$Slot = MentorAvail::find($slot_id);

		$Slot->trainee_id = $id;
		$Slot->position_id = $position;
		$Slot->trainee_comments = Input::get('comments');
		$Slot->save();

		ActivityLog::create(['note' => 'Accepted Session: '.$Slot->slot, 'user_id' => Auth::id(), 'log_state' => 1, 'log_type' => 6]);

		$Slot->sendNewSessionEmail();	
    }

    public function removePublicInfoSection($id) {
        $section = PublicTrainingInfo::find($id);
        $order = $section->order;
        $section->delete();

        $order_updates = PublicTrainingInfo::where('order', '>', $order)->get();
        foreach($order_updates as $o) {
            $o->order = $o->order - 1;
            $o->save();
        }

        $pdfs = PublicTrainingInfoPdf::where('section_id', $id)->get();
        foreach($pdfs as $p) {
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
            'public/training_info', $time.'.'.$ext
        );
        $public_url = '/storage/training_info/'.$time.'.'.$ext;

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
        $controllers = User::where('status', '1')->orderBy('lname', 'ASC')->get()->filter(function($user) {
            if(TrainingTicket::where('controller_id', $user->id)->first() != null || $user->visitor == 0) {
                return $user;
            }
        })->pluck('backwards_name', 'id');
        if($request->id != null) {
            $search_result = User::find($request->id);
        } else {
            $search_result = null;
        }
        if($search_result != null) {
            $tickets_sort = TrainingTicket::where('controller_id', $search_result->id)->get()->sortByDesc(function($t) {
                return strtotime($t->date.' '.$t->start_time);
            })->pluck('id');
            $tickets_order = implode(',',array_fill(0, count($tickets_sort), '?'));
            $tickets = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->paginate(25);
           
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
        $ticket->start_time = $request->start;
        $ticket->end_time = $request->end;
        $ticket->duration = $request->duration;
        $ticket->comments = mb_convert_encoding($request->comments, 'UTF-8'); // character encoding added to protect save method
        $ticket->ins_comments = $request->trainer_comments;
        $ticket->save();
        $extra = null;
	    

	$date = $ticket->date;
	$date = date("Y-m-d");
        $controller = User::find($ticket->controller_id);
        $trainer = User::find($ticket->trainer_id);
	    
	Mail::send(['html' => 'emails.training_ticket'], ['ticket' => $ticket, 'controller' => $controller, 'trainer' => $trainer], function ($m) use ($controller, $ticket) {
            $m->from('training@notams.ztlartcc.org', 'vZTL ARTCC Training Department');
            $m->subject('New Training Ticket Submitted');
            $m->to($controller->email)->cc('ta@ztlartcc.org');
        });
	// Position type must match regex /^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$/ to be accepted by VATUSA    
	if ($request->position == 113 || $request->position == 112){
		$ticket->position = 'ATL_TWR';}
	    elseif ($request->position == 100 || $request->position == 101){
		$ticket->position = 'ZTL_DEL';}
	    elseif ($request->position == 103 || $request->position == 104){
		$ticket->position = 'ATL_DEL';}
	    elseif ($request->position == 102){
		$ticket->position = 'CLT_DEL';}
	    elseif ($request->position == 105){
		$ticket->position = 'ZTL_GND';}
	    elseif ($request->position == 106){
		$ticket->position = 'CLT_GND';}
	    elseif ($request->position == 107 || $request->position == 108){
		$ticket->position = 'ATL_GND';}
	    elseif ($request->position == 109 || $request->position == 110){
		$ticket->position = 'ZTL_TWR';}
	    elseif ($request->position == 111){
		$ticket->position = 'CLT_TWR';}
	    elseif ($request->position == 114 || $request->position == 115){
		$ticket->position = 'ZTL_APP';}
	     elseif ($request->position == 116){
		$ticket->position = 'CLT_APP';}
	     elseif ($request->position == 117 || $request->position == 118 || $request->position == 119){
		$ticket->position = 'ATL_APP';}
	     elseif ($request->position == 120 || $request->position == 121){
		$ticket->position = 'ATL_CTR';}
	     elseif ($request->position == 122){
		$ticket->position = 'ZTL_RCR';}
		elseif ($request->position == 123){
		$ticket->position = 'BHM_APP';}
	// Added http_errors to prevent random errors from being thrown when the VATUSA call fails
	$req_params = [ 'form_params' =>
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
				$res = $client->request('POST', 'https://api.vatusa.net/v2/user/'. $request->controller . '/training/record?apikey=' .Config::get('vatusa.api_key'), $req_params);

        if($request->ots == 1) {
            $ots = new Ots;
            $ots->controller_id = $ticket->controller_id;
            $ots->recommender_id = $ticket->trainer_id;
            $ots->position = $request->position;
            $ots->status = 0;
            $ots->save();
            $extra = ' and the OTS recommendation has been added';
        }
        if($request->monitor == 1) {
            if($request->position == 10 )
                $controller->del = 88;
            elseif ($request->position == 13)
                $controller->del == 89;
            elseif ($request->position == 17)
                $controller->gnd = 88;
            elseif ($request->position == 21)
                $controller->gnd = 89;
            elseif ($request->position == 26)
                $controller->twr = 88;
            elseif ($request->position == 30)
                $controller->twr = 89;
            elseif ($request->position == 35)
                $controller->app = 88;
            elseif ($request->position == 41)
                $controller->app = 89;
            elseif ($request->position == 47)
                $controller->gnd = 89;

            $controller->save();
        }

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' added a training ticket for '.User::find($ticket->controller_id)->full_name.'.';
        $audit->save();


  
        return redirect('/dashboard/training/tickets?id='.$ticket->controller_id)->with('success', 'The training ticket has been submitted successfully'.$extra.'.');
    

    }

    public function viewTicket($id) {
        $ticket = TrainingTicket::find($id);
        return view('dashboard.training.view_ticket')->with('ticket', $ticket);
    }

    public function editTicket($id) {
        $ticket = TrainingTicket::find($id);
        if(Auth::id() == $ticket->trainer_id || Auth::user()->can('snrStaff')) {
            $controllers = User::where('status', '1')->where('canTrain', '1')->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
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
            $ticket->start_time = $request->start;
            $ticket->end_time = $request->end;
            $ticket->duration = $request->duration;
            $ticket->comments = $request->comments;
            $ticket->ins_comments = $request->trainer_comments;
            $ticket->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' edited a training ticket for '.User::find($request->controller)->full_name.'.';
            $audit->save();

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

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' deleted a training ticket for '.User::find($controller_id)->full_name.'.';
            $audit->save();

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

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' accepted an OTS for '.User::find($ots->controller_id)->full_name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'You have sucessfully accepted this OTS. Please email the controller at '.User::find($ots->controller_id)->email.' in order to schedule the OTS.');
    }

    public function rejectRecommendation($id) {
        if(!Auth::user()->can('snrStaff')) {
            return redirect()->back()->with('error', 'Only the TA can reject OTS recommendations.');
        } else {
            $ots = Ots::find($id);
            $ots->delete();

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

            $ins = User::find($ots->ins_id);
            $controller = User::find($ots->controller_id);

            Mail::send('emails.ots_assignment', ['ots' => $ots, 'controller' => $controller, 'ins' => $ins], function ($m) use ($ins, $controller) {
                $m->from('ots-center@notams.ztlartcc.org', 'vZTL ARTCC OTS Center')->replyTo($controller->email, $controller->full_name);
                $m->subject('You Have Been Assigned an OTS for '.$controller->full_name);
                $m->to($ins->email)->cc('ta@ztlartcc.org');
            });

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' assigned an OTS for '.User::find($ots->controller_id)->full_name.' to '.User::find($ots->ins_id)->full_name .'.';
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

        if($ots->ins_id == Auth::id() || Auth::user()->can('snrStaff')) {
            $ext = $request->file('ots_report')->getClientOriginalExtension();
            $time = Carbon::now()->timestamp;
            $path = $request->file('ots_report')->storeAs(
                'public/ots_reports', $time.'.'.$ext
            );
            $public_url = '/storage/ots_reports/'.$time.'.'.$ext;

            $ots->status = $request->result;
            $ots->report = $public_url;
            $ots->save();

            $audit = new Audit;
            $audit->cid = Auth::id();
            $audit->ip = $_SERVER['REMOTE_ADDR'];
            $audit->what = Auth::user()->full_name.' updated an OTS for '.User::find($ots->controller_id)->full_name.'.';
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
        $audit->what = Auth::user()->full_name.' cancelled an OTS for '.User::find($ots->controller_id)->full_name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The OTS has been unassigned from you and cancelled successfully.');
    }
}
