<?php

namespace App\Http\Controllers\Views;
use App\WebData\Announcement;
use App\VatsimData\ATC;
use App\Mship\Bronze;
use App\WebData\Calendar;
use App\Logs\ControllerLog;
use App\Logs\ControllerLogUpdate;
use App\Mship\DiscordUser;
use App\Events\Event;
use App\Events\EventPosition;
use App\Events\EventRegistration;
use App\Mship\Feedback;
use App\Downloads\File;
use App\Mship\Incident;
use App\Mship\Opt;
use App\VatsimData\Overflight;
use App\VatsimData\OverflightUpdate;
use App\Events\PositionPreset;
use App\Mship\Pyrite;
use App\WebData\Scenery;
use App\Training\TrainingTicket;
use App\Mship\User;
use Auth;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mail;
use SimpleXMLElement;
use Response;

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
