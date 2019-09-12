<?php

namespace App\Console\Commands;

use App\MoodleEnrol;
use App\User;
use Illuminate\Console\Command;

class UnenrolMoodleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Unenrol:MoodleUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the enrolment table against the roster after the roster update';

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
        $enrolments = MoodleEnrol::get();

        // Loop through all enrolments. If the user no longer exists, remove their enrolment
        foreach($enrolments as $e) {
            $user = User::where('id', $e->controller_id)->where('status', '!=', '2')->first();

            if(! $user)
                $e->delete();
        }
    }
}
