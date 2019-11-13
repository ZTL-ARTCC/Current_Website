<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class EnrolMoodleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Enrol:MoodleUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enrolls all of the users on the roster in the correct courses';

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
        $controllers = User::where('status', '!=', 2)->get();

        foreach($controllers as $c) {
            $now = Carbon::now()->timestamp;

            // If the user isn't in moodle, add them now...role will be done later
            $m_user = DB::table('mdl_user')->where('id', $c->id)->first();

            if(! $m_user) {
                //Adds user to moodle database
                DB::table('mdl_user')->insert([
                    'id' => $c->id,
                    'confirmed' => 1,
                    'mnethostid' => 1,
                    'username' => $c->id,
                    'firstname' => $c->fname,
                    'lastname' => $c->lname,
                    'email' => $c->email
                ]);
            }

            // Determines which courses should be added for the controller
            if($c->visitor == 1)
                $courses = DB::table('moodle_course_assignments')->where('isVisitor', 1)->get();
            else
                $courses = DB::table('moodle_course_assignments')->where('rating_id', '<=', $c->rating_id)->get();

            foreach($courses as $course) {
                $enrolment = DB::table('mdl_user_enrolments')->where('userid', $c->id)->where('enrolid', $course->mdl_enrol_id)->first();

                if(! $enrolment)
                    DB::table('mdl_user_enrolments')->insert([
                        'status' => 0,
                        'enrolid' => $course->mdl_enrol_id,
                        'userid' => $c->id,
                        'timestart' => $now,
                        'timeend' => 0,
                        'timecreated' => $now,
                        'timemodified' => $now
                    ]);
            }
        }
    }
}
