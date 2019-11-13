<?php

namespace App\Console\Commands;

use Artisan;
use App\User;
use App\Event;
use App\EventPosition;
use App\EventRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class EventEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Event:SendEventReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends event reminder emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now()->format('m/d/Y');
        $event = Event::where('date', $today)->first();

        if($event != null) {
            $positions = EventRegistration::where('status', 1)->where('event_id', $event->id)->get()->sortBy(function($a) use ($event){
                if($a->start_time == null){
                    return $event->start_time;
                } else {
                    return $a->start_time;
                }
            })->sortBy(function($p) {
                return $p->position_name;
            });
            $registrations = EventRegistration::where('event_id', $event->id)->get();
            foreach($registrations as $remind) {
                $r = EventRegistration::find($remind->id);
                if($r->reminder != 1){
                    $user = User::find($r->controller_id);
                    $reg = EventRegistration::where('controller_id', $user->id)->where('event_id', $event->id)->get();
                    $r->reminder = 1;
                    $r->save();
                    foreach($reg as $re) {
                        $re->reminder = 1;
                        $re->save();
                    }

                    Mail::send('emails.event_reminder', ['user' => $user, 'event' => $event, 'positions' => $positions], function($message) use ($user){
                        $message->from('events@notams.ztlartcc.org', 'vZTL ARTCC Events Department')->subject('Upcoming Event Reminder');
                        $message->to($user->email);
                    });
                }
            }
        }
    }
}
