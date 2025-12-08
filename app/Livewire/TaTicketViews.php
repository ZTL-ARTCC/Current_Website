<?php

namespace App\Livewire;

use App\TrainingTicket;
use App\User;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class TaTicketViews extends Component {

    public $students = [];
    public $trainers = [];
    public $student = null;
    public $trainer = null;
    public $start_date = null;
    public $end_date = null;

    public function mount() {
        $controllers_with_tickets = array_flip(TrainingTicket::groupBy('controller_id')->pluck('controller_id')->toArray());
        $this->students = User::where('status', '1')->orderBy('lname', 'ASC')->get()->filter(function ($user) use ($controllers_with_tickets) {
            if (array_key_exists($user->id, $controllers_with_tickets) || $user->visitor == 0) {
                return $user;
            }
        })->pluck('backwards_name', 'id');
        $this->trainers = User::whereHasPermission('train')->where('status', '1')->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        $this->tickets = TrainingTicket::where('trainer_id', 0)->get();
    }

    #[On('update-dates')]
    public function updateDates($field, $value) {
        if ($field == 'startDate') {
            $this->start_date = $value;
        } elseif ($field == 'endDate') {
            $this->end_date = $value;
        }
    }

    public function render() {
        $tickets = collect();
        if ($this->student || $this->trainer || ($this->start_date && $this->end_date)) {
            $query = TrainingTicket::query();
            $query->when($this->student, function ($query, $value) {
                $query->where('controller_id', $value);
            });

            $query->when($this->trainer, function ($query, $value) {
                $query->where('trainer_id', $value);
            });

            if ($this->start_date && $this->end_date) {
                $start_date = Carbon::parse($this->start_date);
                $end_date = Carbon::parse($this->end_date);
                $query->whereBetween('start_date', [$start_date, $end_date]);
            }

            $tickets = $query->get();
        }
        return view('livewire.ta-ticket-views')->with('tickets', $tickets);
    }
}
