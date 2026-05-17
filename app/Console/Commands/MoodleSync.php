<?php

namespace App\Console\Commands;

use App\User;
use App\Visitor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoodleSync extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:moodle-sync {visit_request_cid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync ZTL roster to Moodle and assign cohorts';

    const REQUIRED_COHORTS = ['Home', 'Visiting', 'Visit Request'];
    private $cohorts = [];

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->check_cohorts_table(); // Verify cohorts setup
        if ($this->argument('visit_request_cid')) {
            $this->add_visit_request_cohort(intval($this->argument('visit_request_cid')));
            return;
        }
        $this->sync_roster(); // Sync the facility roster to Moodle
        $this->sync_visit_requests(); // Sync pending visit requests to Moodle
    }
        
    private function check_cohorts_table(): void {
        // Check to ensure required cohorts exist. If not, create them.
        $cohorts = DB::connection('moodle')->table('cohort')->select(['id', 'name'])->pluck('name', 'id')->toArray();
        $insert = false;
        foreach (self::REQUIRED_COHORTS as $req_cohort) {
            if (!in_array($req_cohort, $cohorts)) {
                $this->info('Creating cohort: ' . $req_cohort);
                DB::connection('moodle')->table('cohort')->insert([
                    'name' => $req_cohort,
                    'idnumber' => str_replace(' ', '', strtolower($req_cohort)),
                    'contextid' => 1,
                    'descriptionformat' => 1,
                    'timecreated' => time(),
                    'timemodified' => time()
                    ]);
                $insert = true;
            }
        }
        if ($insert) {
            $cohorts = DB::connection('moodle')->table('cohort')->select(['id', 'name'])->pluck('name', 'id')->toArray();
        }
        $this->cohorts = array_flip($cohorts); // Index the array by cohort name
        $this->info('Cohorts table verified.');
    }

    private function sync_roster(): void {
        $this->info('Syncing facility roster to Moodle user table.');
        $roster = User::all();
        foreach ($roster as $user) {
            $moodle_user_id = $this->update_moodle_user($user);
            $cohort_id = null;
            if ($user->status == 1) {
                $cohort_id = $this->cohorts['Home'];
            } elseif ($user->status == 2) {
                $cohort_id = $this->cohorts['Visiting'];
            }
            $this->assign_cohort_to_user($moodle_user_id, $cohort_id);
        }
    }

    private function sync_visit_requests(): void {
        $this->info('Syncing visting requests to Moodle user table.');
        $visit_requests = Visitor::whereNot('status', 1)->get();
        foreach ($visit_requests as $visit_req) {
            $moodle_user_id = $this->update_moodle_user($visit_req);
            $cohort_id = ($visit_req->status == 0) ? $this->cohorts['Visit Request'] : null;
            $this->assign_cohort_to_user($moodle_user_id, $cohort_id);
        }
    }

    private function add_visit_request_cohort(int $cid): void {
        $this->info('Adding ' . $cid . ' to the visit request cohort');
        $visitor = Visitor::where('cid', $cid)->where('status', 0)->first();
        if (!$visitor) {
            $this->error('Unable to add ' . $cid . ' to the visit request cohort -- does not exist in visit request table!');
            return;
        }
        $moodle_user_id = $this->update_moodle_user($visitor);
        $this->assign_cohort_to_user($moodle_user_id, $this->cohorts['Visit Request']);
        $this->info('Visit request user added to Moodle users with appropriate cohort.');
    }

    private function update_moodle_user(User|Visitor $user): null|int {
        $isHomeController = (class_basename($user) == 'User');
        $cid = $isHomeController ? $user->id : $user->cid;
        $first_name = $isHomeController ? $user->fname : $user->name;
        $last_name = $isHomeController ? $user->lname : '';
        $suspended = $isHomeController ? (($user->status == 0) ? 1 : 0) : (($user->status == 2) ? 1 : 0);
        $timezone = $isHomeController ? $user->timezone : '99';
        $moodle_user = DB::connection('moodle')->table('user')->select(['id'])->where('username', $cid)->pluck('id')->toArray();
        if (count($moodle_user) == 0 && $isHomeController && $user->status == 0) {
            // No reason to sync inactive users
            return null;
        }
        $this->info('Updating Moodle user ' . $cid);
        $query_parameters = [
                'auth' => 'oauth2',
                'firstname' => $first_name,
                'lastname' => $last_name,
                'email' => $user->email,
                'suspended' => $suspended,
                'timezone' => $timezone,
                'timemodified' => time()
        ];
        if (count($moodle_user) == 0) {
            $query_parameters['timecreated'] = time();
        }
        $moodle_user = DB::connection('moodle')->table('user')->updateOrInsert(
            ['id' => (count($moodle_user) > 0) ? $moodle_user[0] : null, 'username' => $cid],
            $query_parameters
        );
        $moodle_user = DB::connection('moodle')->table('user')->where('username', $cid)->first();
        return $moodle_user->id;
    }

    private function assign_cohort_to_user(int $moodle_user_id, int|null $cohort_id): void {
        // In this implementation, user may only have 1 of the 3 available cohorts
        if (is_numeric($cohort_id)) {
            DB::connection('moodle')->table('cohort_members')->updateOrInsert(
                ['userid' => $moodle_user_id],
                ['cohortid' => $cohort_id, 'timeadded' => time()]
            );
            $this->info('Assigning moodle cohort for ' . $moodle_user_id);
        } else {
            $this->info('Deleting moodle cohort for ' . $moodle_user_id);
            DB::connection('moodle')->table('cohort_members')->where('userid', $moodle_user_id)->delete();
        }
    }
}
