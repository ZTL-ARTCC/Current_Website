<?php
namespace App\Http\Controllers;

use App\MentorAvail;
use App\User;
use Mail;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class TrainingController extends Controller {
	public function cancelSession($id)
	{
		$session = MentorAvail::find($id);
		$session->sendCancellationEmail();
		$session->trainee_id = null;
		$session->position_id = null;
		$session->trainee_comments = null;
		$session->save();
		return View('dashboard.training.sch.index')->with('success', 'Training session canceled!');
	}
	public function showRequests()
	{
		$id = Auth::id();
		$time = Carbon::now('America/New_York');
		$sessions = MentorAvail::with('mentor')->where('trainee_id', $id)->where('slot', '>', $time)->get();
		return View('dashboard.training.sch.index')->with('sessions', $sessions);
	}
	public function showMentAvail()
	{
		$id = Auth::id();
		$availability = MentorAvail::with('mentor')
			->whereNull('trainee_id')
			->where('slot', '>', Carbon::now('America/New_York'))
			->get();
		return View('dashboard.training.sch.mtr_avail')->with('availability', $availability);
    }
	public function saveSession()
	{
		$id = Auth::id();
		$nSessions = MentorAvail::where('trainee_id', $id)->where('slot', '>', Carbon::now())->count();
		$slot_id = Input::get('slot');
		$Slot = MentorAvail::find($slot_id);
		$Slot->trainee_id = $id;
		$Slot->trainee_comments = Input::get('comments');
		$Slot->position_id =  Input::get('position');
		$Slot->save();
		
		Mail::send('emails.training.new_session', ['Slot' => $Slot], function($message) use ($Slot){
			$message->from('training@notams.ztlartcc.org', 'vZTL ARTCC Training Department')->subject('ZTL ARTCC - New Seesion');
			$message->to($Slot->trainee->email)->cc($Slot->mentor->email);
		});
		return Redirect::action('TrainingController@showRequests')->with('success', 'Booking Created, you will receive a email shortly');
	}
	
	
}