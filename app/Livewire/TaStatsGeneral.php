<?php

namespace App\Livewire;

use App\TrainingTicket;
use Carbon\Carbon;
use Livewire\Component;

class TaStatsGeneral extends Component
{
    public $no_shows;
    public $tickets_by_certs;
    public $completion_ratios;
    public \StdClass $lookback_monthly;
    public \StdClass $lookback_annual;
    private $cert_types = ['S1', 'S2', 'S3', 'C1'];

    public function render()
    {
        $this->generate_stats();
        return view('livewire.ta-stats-general');
    }

    private function generate_stats(): void
    {
        $this->generate_no_shows();
        $this->generate_tickets_by_certification();
        $this->generate_completion_ratios();
        $this->generate_lookback();
        // Data for graphics testing only!
        $this->tickets_by_certs = [7, 12, 32, 12];
        $this->completion_ratios = [0.9, 0.95, 0.85, 0.75];
        $this->lookback_annual->last_year = [99, 85, 102, 77, 98, 99, 110, 81, 97, 100, 111, 99];
        $this->lookback_annual->this_year = [100, 99, 98, 100, 102, 105, 110, 99, 98, 85, 86, 90]; 
        $this->lookback_monthly->now = [99, 200, 300];
        $this->lookback_monthly->prev = [85, 185, 280];   
    }

    private function generate_no_shows(): void
    {
        $by_controller = [];
        $no_show_sessions = TrainingTicket::where('type', 10)->where('date', '<=', Carbon::now()->addDays(45)->toDateTimeString())->get();
        foreach ($no_show_sessions as $no_show_session) {
            $by_controller[] = $no_show_session->controller_id;
        }
        $more_than_one_no_show = array_count_values($by_controller);
        if (max($more_than_one_no_show) < 2) {
            $this->no_shows = [];
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

    private function generate_tickets_by_certification(): void
    {
        $position_types_by_rating = TrainingTicket::$position_types_by_rating;
        $tickets = TrainingTicket::where('date', '<=', Carbon::now()->addYears(2)->toDateTimeString())->get();
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

    private function generate_completion_ratios(): void
    {
        $position_types_by_rating = TrainingTicket::$position_types_by_rating;
        $tickets = TrainingTicket::where('date', '<=', Carbon::now()->addYears(2)->toDateTimeString())->get();
        foreach ($this->cert_types as $cert) {
            $tickets_complete = $tickets->where('type', 12)->whereIn('position', $position_types_by_rating[$cert])->count();
            $tickets_incomplete = $tickets->where('type', 13)->whereIn('position', $position_types_by_rating[$cert])->count();
            $total = $tickets_complete + $tickets_incomplete;
            $this->completion_ratios[$cert] = $tickets_complete . '/' . $total;
        }
    }

    private function generate_lookback(): void
    {
        // Annual Lookback
        $selectDate = Carbon::now('America/New_York')->startofMonth()->subMonth(24);
        $this->lookback_annual = new \StdClass;
        $this->lookback_annual->last_year = [];
        
        while (count($this->lookback_annual->last_year) < 12) {
            $this->lookback_annual->last_year[] = TrainingTicket::where('date', '>=', $selectDate->copy()->startofMonth()->toDateTimeString())->where('date', '<=', $selectDate->copy()->endofMonth()->toDateTimeString())->count();
            $this->lookback_annual->labels[] = $selectDate->format('F');
            $selectDate = $selectDate->addMonth(1);
        }
        $selectDate = Carbon::now('America/New_York')->startofMonth()->subMonth(11);
        $this->lookback_annual->this_year = [];
        while (count($this->lookback_annual->this_year) < 12) {
            $this->lookback_annual->this_year[] = TrainingTicket::where('date', '>=', $selectDate->copy()->startofMonth()->toDateTimeString())->where('date', '<=', $selectDate->copy()->endofMonth()->toDateTimeString())->count();
            $selectDate = $selectDate->addMonth(1);
        }

        // 30/60/90 Lookback
        $this->lookback_monthly = new \StdClass;
        // Current 30, Previous 30
        $selectDate = Carbon::now('America/New_York')->subDays(30);
        $this->lookback_monthly->now[] = TrainingTicket::where('date', '>=', $selectDate->toDateTimeString())->where('date', '<=', $selectDate->addDays(30)->toDateTimeString())->count();
        $selectDate = Carbon::now('America/New_York')->subDays(60);
        $this->lookback_monthly->prev[] = TrainingTicket::where('date', '>=', $selectDate->toDateTimeString())->where('date', '<=', $selectDate->addDays(30)->toDateTimeString())->count();
        // Current 60, Previous 60
        $selectDate = Carbon::now('America/New_York')->subDays(60);
        $this->lookback_monthly->now[] = TrainingTicket::where('date', '>=', $selectDate->toDateTimeString())->where('date', '<=', $selectDate->addDays(60)->toDateTimeString())->count();
        $selectDate = Carbon::now('America/New_York')->subDays(120);
        $this->lookback_monthly->prev[] = TrainingTicket::where('date', '>=', $selectDate->toDateTimeString())->where('date', '<=', $selectDate->addDays(60)->toDateTimeString())->count();
        // Current 90, Previous 90
        $selectDate = Carbon::now('America/New_York')->subDays(90);
        $this->lookback_monthly->now[] = TrainingTicket::where('date', '>=', $selectDate->toDateTimeString())->where('date', '<=', $selectDate->addDays(90)->toDateTimeString())->count();
        $selectDate = Carbon::now('America/New_York')->subDays(180);
        $this->lookback_monthly->prev[] = TrainingTicket::where('date', '>=', $selectDate->toDateTimeString())->where('date', '<=', $selectDate->addDays(90)->toDateTimeString())->count();
    }
}
