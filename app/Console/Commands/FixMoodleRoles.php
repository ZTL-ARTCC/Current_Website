<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FixMoodleRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Moodle:FixRoles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes the Moodle roles because it\'s been broken.';

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
        DB::table('mdl_role_assignments')->truncate();
        $users = User::where('status', '!=', 2)->get();

        foreach($users as $u) {
            if ($u->rating_id == 1) {
                $mdl_rating = 18;
            } elseif ($u->rating_id == 2) {
                $mdl_rating = 9;
            } elseif ($u->rating_id == 3) {
                $mdl_rating = 10;
            } elseif ($u->rating_id == 4) {
                $mdl_rating = 11;
            } elseif ($u->rating_id == 5) {
                $mdl_rating = 12;
            } elseif ($u->rating_id == 7 || $u->rating_id == 11 || $u->rating_id == 12) {
                $mdl_rating = 13;
            } elseif ($u->rating_id == 8 || $u->rating_id == 10) {
                $mdl_rating = 14;
            } else {
                $mdl_rating = 0;
            }

            $now = Carbon::now()->timestamp;
            DB::table('mdl_role_assignments')->insert([
                'roleid' => $mdl_rating,
                'contextid' => 1,
                'userid' => $u->id,
                'modifierid' => 1,
                'timemodified' => $now
            ]);

            // Check for mentor
            if($u->hasRole('mtr')) {
                $now = Carbon::now()->timestamp;
                DB::table('mdl_role_assignments')->insert([
                    'roleid' => 15,
                    'contextid' => 1,
                    'userid' => $u->id,
                    'modifierid' => 1,
                    'timemodified' => $now
                ]);
            }

            // Check for staff
            if($u->can('snrStaff')) {
                $now = Carbon::now()->timestamp;
                DB::table('mdl_role_assignments')->insert([
                    'roleid' => 17,
                    'contextid' => 1,
                    'userid' => $u->id,
                    'modifierid' => 1,
                    'timemodified' => $now
                ]);
            } elseif($u->can('staff')) {
                $now = Carbon::now()->timestamp;
                DB::table('mdl_role_assignments')->insert([
                    'roleid' => 16,
                    'contextid' => 1,
                    'userid' => $u->id,
                    'modifierid' => 1,
                    'timemodified' => $now
                ]);
            }
        }
    }
}
