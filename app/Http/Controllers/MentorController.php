<?php
namespace App\Http\Controllers;

use App\MentorAvail;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class MentorController extends Controller {
    public function showAvail()
	{
		$availability = MentorAvail::where('mentor_id', '=', Auth::id())->get();
		return View('dashboard.training.mentors.mentoravail')->with('availability', $availability);
    }
    
    public function postAvail()
	{
		$mentor_id = Auth::id();
		$slots = Input::get('slots');
		$today = new Carbon("midnight today", 'America/New_York');

		$availability = MentorAvail::where('mentor_id', '=', $mentor_id)->where('slot', '>=', $today)->get();

		if (!$slots) $slots = [];

		// Slots to be added

		$new_slots = array_diff($slots, $availability->Pluck('slot')->toArray());
		// Slots to be deleted
		$old_slots = array_diff($availability->Pluck('slot')->toArray(), $slots);

		foreach ($new_slots as $slot) {
			MentorAvail::create([
				'mentor_id' => $mentor_id,
				'slot' => $slot,
			]);
		}

		MentorAvail::where('mentor_id', '=', $mentor_id)->whereIn('slot', $old_slots)->delete();
		

		
    }
}