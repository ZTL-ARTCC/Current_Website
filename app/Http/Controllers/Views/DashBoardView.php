<?php

namespace App\Http\Controllers\Views;
use App\WebData\Calander;

class DashView extends \App\Http\Controllers\Controller
{
    public function showTeamspeak() {
        return view('dashboard.controllers.teamspeak');
    }
    
    public function showCalendarEvent($id) {
        $calendar = Calendar::find($id);

        return view('dashboard.controllers.calendar')->with('calendar', $calendar);
    }
}
