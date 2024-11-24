<?php

namespace App\Console\Commands;

use App\AcademyExam;
use App\User;
use Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class UpdateAcademyExams extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RosterUpdate:UpdateAcademyExams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the academy exams for active controllers on the roster';

    /**
     * Execute the console command.
     */
    public function handle() {
        $active_users = User::where('status', User::STATUSES['ACTIVE'])->where(function (Builder $query) {
            $query->where('rating_id', User::RATINGS['OBS'])
                  ->orWhere('rating_id', User::RATINGS['S1'])
                  ->orWhere('rating_id', User::RATINGS['S2'])
                  ->orWhere('rating_id', User::RATINGS['S3']);
        })->get();

        foreach ($active_users as $user) {
            $this->updateAcademyExams($user);
        }
    }

    private function updateAcademyExams($user) {
        $req_params = [
            'form_params' => [],
            'http_errors' => false
        ];

        $client = new Client();
        $res = $client->request('GET', Config::get('vatusa.base').'/v2/academy/transcript/' . $user->id . '?apikey=' . Config::get('vatusa.api_key'), $req_params);

        $academy = json_decode((string) $res->getBody(), true);
        $exam_names = AcademyExam::$EXAM_NAMES;

        $existing_exams = [];
        foreach ($user->academyExams as $exam) {
            $existing_exams[$exam->name] = $exam;
        }

        foreach ($exam_names as $exam_name) {
            $changes = false;
            $exam = null;

            if (!array_key_exists($exam_name, $existing_exams)) {
                $exam = new AcademyExam;
                $exam->controller_id = $user->id;
                $exam->name = $exam_name;
                $changes = true;
            } else {
                $exam = $existing_exams[$exam_name];
            }

            if (isset($academy['data'][$exam_name])) {
                foreach ($academy['data'][$exam_name] as $exam_attempt) {
                    if (is_null($exam->date) || ($exam_attempt['grade'] > $exam->grade)) {
                        $exam->date = date("m/d/y", $exam_attempt['time_finished']);
                        $exam->success = ($exam_attempt['grade'] >= 80) ? 1 : 0;
                        $exam->grade = $exam_attempt['grade'];
                        $changes = true;
                    }
                }
            }

            if ($changes) {
                $exam->save();
            }
        }
    }
}
