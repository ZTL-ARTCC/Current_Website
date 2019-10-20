<?php
namespace App\Http\Controllers;

use App\MentorAvai;
use App\User;

class TrainingController extends Controller {
    public function showMentAvail()
	{
		return View('dashboard.training.sch.mtr_avail');
    }

}