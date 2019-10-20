<?php
namespace App\Http\Controllers;

class TrainingController extends BaseController {
    public function showMentAvail()
	{
		return View('dashboard.training.sch.mentoravail')->with('availability', $availability);
    }
}