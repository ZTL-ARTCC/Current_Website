<?php

namespace App\Console\Commands;

use App\User;
use Config;
use DB;
use Illuminate\Console\Command;

class DeleteMoodleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Moodle:DeleteUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes any Moodle user that is no longer on the roster.';

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
        if(Config::get('app.moodle') == 1) {
            $moodle_users = DB::table('mdl_user')->where('deleted', 0)->get();

            foreach ($moodle_users as $m) {
                $controller = User::find($m->id);
                if($controller && $controller->status == 2) {
                    DB::table('mdl_user')->where('id', $m->id)->update(['deleted' => 1]);
                }
            }
        }
    }
}
