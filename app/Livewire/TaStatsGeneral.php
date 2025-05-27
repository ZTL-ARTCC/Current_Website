<?php

namespace App\Livewire;

use App\TrainingTicket;
use Carbon\Carbon;
use Livewire\Component;

class TaStatsGeneral extends Component {
    public $no_shows;
    public $tickets_by_certs;
    public $session_ids;
    public $completion_ratios;
    public \StdClass $lookback_monthly;
    public \StdClass $lookback_annual;
    private $cert_types = ['S1', 'S2', 'S3', 'C1'];
    public $position_select;

    public function render() {
        $this->generate_stats();
        return view('livewire.ta-stats-general');
    }

    private function generate_stats(): void {
        $this->generate_no_shows();
        $this->generate_tickets_by_certification();
        $this->generate_completion_ratios();
        $this->generate_lookback();
    }

    private function generate_no_shows(): void {
        $by_controller = [];
        $this->no_shows = [];
        $no_show_sessions = TrainingTicket::where('type', 10)->where('start_date', '<=', Carbon::now()->addDays(45)->toDateTimeString())->get();
        foreach ($no_show_sessions as $no_show_session) {
            $by_controller[] = $no_show_session->controller_id;
        }
        $more_than_one_no_show = array_count_values($by_controller);
        if (count($more_than_one_no_show) == 0 || max($more_than_one_no_show)) {
            return;
        }
        foreach ($more_than_one_no_show as $controller => $number_no_shows) {
            if ($number_no_shows < 2) {
                continue;
            }
            $controller_no_shows = $no_show_sessions->where('controller_id', $controller)->get();
            $controller_name = 'Unknown';
            $controller_no_show_events = [];
            foreach ($controller_no_shows as $no_show) {
                $controller_no_show_events[] = $no_show->date;
            }
            $this->no_shows[] = $controller_name . ' no-showed sessions on the following dates: ' . implode(', ', $controller_no_show_events);
        }
    }

    private function generate_tickets_by_certification(): void {
        $position_types_by_rating = TrainingTicket::$position_types_by_rating;
        $tickets = TrainingTicket::where('start_date', '<=', Carbon::now()->addYears(2)->toDateTimeString())->get();
        foreach ($this->cert_types as $cert) {
            $cert_tickets = $tickets->whereIn('position', $position_types_by_rating[$cert])->pluck('controller_id')->toArray();
            $tickets_by_controller = array_count_values($cert_tickets);
            if (count($tickets_by_controller) == 0) {
                $this->tickets_by_certs[$cert] = 0;
                continue;
            }
            $this->tickets_by_certs[$cert] = array_sum($tickets_by_controller) / count($tickets_by_controller);
        }
    }

    private function generate_completion_ratios(): void {
        $this->completion_ratios = $this->session_ids = [];
        if ($this->position_select == '') {
            $this->position_select = 'S1';
        }
        $sorted_session_ids = TrainingTicket::$session_ids_by_category[$this->position_select];
        foreach ($sorted_session_ids as $session_id) {
            $tickets = TrainingTicket::where('start_date', '<=', Carbon::now()->addYears(2)->toDateTimeString())->where('session_id', $session_id)->get();
            $tickets_complete = $tickets->where('type', 12)->count();
            $tickets_incomplete = $tickets->where('type', 13)->count();
            $total = $tickets_complete + $tickets_incomplete;
            $this->completion_ratios[] = $tickets_complete / $total;
            $this->session_ids[] = TrainingTicket::$session_ids[$session_id];
        }
        $this->dispatch('updateCompletionChart', labels: $this->session_ids, data: $this->completion_ratios);
    }

    private function generate_lookback(): void {
        // Annual Lookback
        $selectDate = Carbon::now('America/New_York')->startofMonth()->subMonth(24);
        $this->lookback_annual = new \StdClass;
        $this->lookback_annual->last_year = [];
        
        while (count($this->lookback_annual->last_year) < 12) {
            $this->lookback_annual->last_year[] = TrainingTicket::whereBetween('start_date', [$selectDate->copy()->startofMonth()->toDateTimeString(), $selectDate->copy()->endofMonth()->toDateTimeString()])->count();
            $this->lookback_annual->labels[] = $selectDate->format('F');
            $selectDate = $selectDate->addMonth(1);
        }
        $selectDate = Carbon::now('America/New_York')->startofMonth()->subMonth(11);
        $this->lookback_annual->this_year = [];
        while (count($this->lookback_annual->this_year) < 12) {
            $this->lookback_annual->this_year[] = TrainingTicket::whereBetween('start_date', [$selectDate->copy()->startofMonth()->toDateTimeString(), $selectDate->copy()->endofMonth()->toDateTimeString()])->count();
            $selectDate = $selectDate->addMonth(1);
        }

        // 30/60/90 Lookback
        $this->lookback_monthly = new \StdClass;
        // Current 30, Previous 30
        $selectDate = Carbon::now('America/New_York')->subDays(30);
        $this->lookback_monthly->now[] = TrainingTicket::whereBetween('start_date', [$selectDate->toDateTimeString(), $selectDate->addDays(30)->toDateTimeString()])->count();
        $selectDate = Carbon::now('America/New_York')->subDays(60);
        $this->lookback_monthly->prev[] = TrainingTicket::whereBetween('start_date', [$selectDate->toDateTimeString(), $selectDate->addDays(30)->toDateTimeString()])->count();
        // Current 60, Previous 60
        $selectDate = Carbon::now('America/New_York')->subDays(60);
        $this->lookback_monthly->now[] = TrainingTicket::whereBetween('start_date', [$selectDate->toDateTimeString(), $selectDate->addDays(60)->toDateTimeString()])->count();
        $selectDate = Carbon::now('America/New_York')->subDays(120);
        $this->lookback_monthly->prev[] = TrainingTicket::whereBetween('start_date', [$selectDate->toDateTimeString(), $selectDate->addDays(60)->toDateTimeString()])->count();
        // Current 90, Previous 90
        $selectDate = Carbon::now('America/New_York')->subDays(90);
        $this->lookback_monthly->now[] = TrainingTicket::whereBetween('start_date', [$selectDate->toDateTimeString(), $selectDate->addDays(90)->toDateTimeString()])->count();
        $selectDate = Carbon::now('America/New_York')->subDays(180);
        $this->lookback_monthly->prev[] = TrainingTicket::whereBetween('start_date', [$selectDate->toDateTimeString(), $selectDate->addDays(90)->toDateTimeString()])->count();
    }
}
