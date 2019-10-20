<?php
namespace App\Http\Controllers;

use App\MentorAvai;
use App\User;

class TrainingController extends Controller {
    public function showMentAvail()
	{
		return View('admin.training.mentoravail')->with('availability', $availability);
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