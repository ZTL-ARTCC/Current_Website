<?php
namespace App\Http\Controllers;

class TrainingController extends Controller {
    public function showMentAvail()
	{
		return View('dashboard.training.sch.mentoravail')->with('availability', $availability);
    }
}