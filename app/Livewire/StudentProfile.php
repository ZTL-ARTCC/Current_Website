<?php

namespace App\Livewire;

use App\ControllerLog;
use App\Http\Controllers\TrainingDash;
use App\Ots;
use App\SoloCert;
use App\StudentNotes;
use App\TrainingTicket;
use App\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class StudentProfile extends Component {
    #[Url]
    public $id = '';

    public User $user;
    public string $avatar = '';
    public $rating_badge = '';
    public $home_status = '';
    public $exams;
    public $examTypes = ['BASIC', 'S2', 'S3', 'C1'];
    public $student_note;
    public $appointments = [];
    public $appointments_successful = false;
    public $controller_activity;
    public $last_control_str = '';
    public $ots_solo_str = '';
    public $ticket_select;
    public $tickets;

    public function render() {
        $this->user = User::find($this->id);
        $this->exams = $this->user->getAcademyExamTranscript();
        $this->student_note = StudentNotes::find($this->id);
        $this->getAvatar();
        $this->generateRatingBadge();
        $this->getHomeVisitorStatus();
        $this->fetchScheddyAppointments();
        $this->fetchControllerActivity();
        $this->generateLastControlString();
        $this->checkOtsSoloStatus();
        $this->fetchTickets();
        return view('livewire.student-profile');
    }

    private function getAvatar() {
        if ($this->user->discord != null) {
            try {
                $client = new Client();
                $response = $client->request('GET', "https://discord.com/api/v9/users/" . $this->user->discord, [
                    'headers' => [
                        'Authorization' => "Bot " . config('discord.token'),
                    ]
                ]);

                $d_user_data = json_decode($response->getBody()->getContents());
                if ($d_user_data->avatar != null) {
                    $this->avatar = '<img src="https://cdn.discordapp.com/avatars/' . $this->user->discord . '/' . $d_user_data->avatar . '.png" class="rounded" alt="Avatar">';
                    return;
                }
            } catch (Exception $e) {
                // No handling necessary
            }
        }
        $this->avatar = '<i class="fa-solid fa-user fa-10x"></i>';
    }

    private function generateRatingBadge() {
        $color = 'btn-purple';
        if (in_array($this->user->rating_short, ['OBS'])) {
            $color = 'text-bg-secondary';
        }
        if (in_array($this->user->rating_short, ['S1','S2','S3'])) {
            $color = 'text-bg-warning';
        }
        if (in_array($this->user->rating_short, ['C1','C3'])) {
            $color = 'text-bg-success';
        }
        if (in_array($this->user->rating_short, ['I1','I3'])) {
            $color = 'text-bg-danger';
        }
        $this->rating_badge = '<span class="badge ' . $color . '">' . $this->user->rating_short . '</span>';
    }

    private function getHomeVisitorStatus() {
        $this->home_status = 'home';
        if ($this->user->visitor == 1) {
            $this->home_status = 'visiting (' . $this->user->visitor_from . ')';
        }
        $since = Carbon::createFromFormat('Y-m-d H:i:s', $this->user->added_to_facility);
        $this->home_status .= ' controller since <strong>' . $since->format('F j, Y') . '</strong>';
    }

    private function fetchScheddyAppointments() {
        $client = new Client();
        try {
            $res = $client->get(
                config('scheddy.base').'/api/userSessions/'.$this->user->id,
                ['headers' => [
                    'Authorization' => 'Bearer '.config('scheddy.api_key')
                ],
                'http_errors' => false
                ]
            );

            if ($res->getStatusCode() == "200") {
                $this->appointments = json_decode($res->getBody());
                $this->appointments_successful = true;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error($e);
        }
    }

    private function fetchControllerActivity() {
        $this->controller_activity = ControllerLog::where('cid', $this->user->id)->latest()->take(5)->get();
    }

    private function generateLastControlString() {
        if (count($this->controller_activity) == 0) {
            $this->last_control_str = 'no activity logged';
            return;
        }
        $this->last_control_str = $this->controller_activity->last()->position;
        $this->last_control_str .= ' on ' . $this->controller_activity->last()->updated_at->format('F j, Y');
    }

    private function checkOtsSoloStatus() {
        $this->ots_solo_str = '';
        $ots = Ots::where('controller_id', $this->user->id)->where('status', 1)->first();
        $solo = SoloCert::where('cid', $this->user->id)->where('status', 1)->first();
        if ($ots) {
            $this->ots_solo_str .= 'Recommended for OTS - ' . $ots->position_name . ' on ' . $ots->created_at->format('F j, Y');

        }
        if ($solo) {
            if (strlen($this->ots_solo_str) > 0) {
                $this->ots_solo_str .= '<br>';
            }
            $this->ots_solo_str .= 'Solo certification - ' . User::soloPositions()[$solo->pos] . ' thru ' . Carbon::createFromFormat('Y-m-d', $solo->expiration)->format('F j, Y');

        }
    }

    public function fetchTickets() {
        $this->tickets = [];
        if ($this->ticket_select == null) {
            $this->ticket_select = 'S1';
        }
        $tickets_sort = TrainingTicket::where('controller_id', $this->user->id)->get()->sortByDesc(function ($t) {
            return strtotime($t->date . ' ' . $t->start_time);
        })->pluck('id');

        if (! $tickets_sort->isEmpty()) {
            $tickets_order = implode(',', array_fill(0, count($tickets_sort), '?'));
            $tickets = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->get();
            foreach ($tickets as &$t) {
                $t->position = TrainingDash::legacyTicketTypes($t->position);
                $t->sort_category = TrainingDash::getTicketSortCategory($t->position, $t->draft);
            }
            //dd($tickets);
            $this->tickets = $tickets->where('sort_category', $this->ticket_select);
        }
    }
}
