<?php
namespace App\Http\Controllers;

use App\MentorAvail;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class TrainingController extends Controller {
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
}