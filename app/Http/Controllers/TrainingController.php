<?php
namespace App\Http\Controllers;

class TrainingController extends Controller {
    public function showMentAvail()
	{
		return View('dashboard.training.sch.mtr_avail')->with('availability', $availability);
    }
}