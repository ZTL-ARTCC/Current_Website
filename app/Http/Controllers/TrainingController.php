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
		$mentor = User::find($Slot->mentor_id);
		$trainee = User::find($Slot->trainee_id);
		$Slot->save();
	}
	
	public function cancelSession($id)
	{
		$session = MentorAvail::find($id);
		$session->sendCancellationEmail();
		$session->trainee_id = null;
		$session->position_id = null;
		$session->trainee_comments = null;
		$session->save();

		ActivityLog::create(['note' => 'Cancelled Session: '.$session->slot, 'user_id' => Auth::id(), 'log_state' => 3, 'log_type' => 6]);

		return Redirect::to('/training')->with('message', 'Training sessions canceled!');
	}

	public function showNotes()
	{
		$id = Auth::id();
		$user = User::find($id);
		$notes = TrainingNote::where('controller_id', $id)->orderBy('created_at', 'ASC')->get();
		$exam = Exam::where('controller_id', '=', $id)->orderBy('updated_at', 'ASC')->get();
		return View('admin.training.notes')->with('notes', $notes)->with('user', $user)->with('exam', $exam);
	}

	public function showNote($id)
	{
		$note = TrainingNote::find($id);
		return View('admin.training.note')->with('note', $note);
	}
}