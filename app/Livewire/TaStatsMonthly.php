<?php

namespace App\Livewire;

use App\Http\Controllers\TrainingDash;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Config;
use Livewire\Component;
use stdClass;

class TaStatsMonthly extends Component {
    public $stats;
    public $graph_stats;
    public $graph_data;
    public $date_select;
    public $todays_date;
    public $inclusive_dates;
    public $trainer_min_sessions = 0;
    public $year_of_months_lookback = [];

    public function render() {
        $this->todays_date = Carbon::now()->format('n/j/Y');
        $year_select = $month_select = null;
        $date_part = explode(' ', $this->date_select);
        $month_select = Carbon::now()->format('m');
        $year_select = Carbon::now()->format('Y');
        $change_selected_month = false;
        if (count($date_part) == 2) {
            $change_selected_month = true;
            $month_select = $date_part[0];
            $year_select = $date_part[1];
        }
        $selDateStart = Carbon::createFromFormat('m/d/Y', $month_select . '/01/' . $year_select);
        $this->inclusive_dates = $selDateStart->startofMonth()->format('n/j/Y') . ' - ' . $selDateStart->endofMonth()->format('n/j/Y');
        $this->stats = TrainingDash::generateTrainingStats($year_select, $month_select, 'stats');
        $this->graph_stats = TrainingDash::generateTrainingStats($year_select, $month_select, 'graph');
        $this->graph_data = new stdClass();
        $this->build_session_duration_data();
        $this->students_requiring_training();
        $this->sessions_per_month();
        $this->sessions_by_staff();
        if ($change_selected_month) {
            $this->dispatch('changeSelectedMonth', inclusive_dates: $this->inclusive_dates, graph_data: $this->graph_data);
            $this->skipRender();
        }
        $this->trainer_min_sessions = Config::get('ztl.trainer_min_sessions');
        $this->lookback_months();
        return view('livewire.ta-stats-monthly');
    }

    public function rendered() {
        $this->dispatch('load-charts');
    }

    public function lookback_months(): void {
        $lookback_dates = array_reverse(CarbonPeriod::create(now()->subMonths(11), '1 month', now())->toArray());
        foreach ($lookback_dates as $date) {
            $this->year_of_months_lookback[$date->format('m Y')] = $date->format('M Y');
        }
    }

    public function build_session_duration_data(): void {
        $session_avg_time = $session_id = [];
        foreach ($this->graph_stats['sessionDuration'] as $sesh_type) {
            $s_id_exp = explode(' ', $sesh_type[0]);
            if ($s_id_exp[0] == 'Unlisted/other') {
                $session_id[] = 'Other';
            } else {
                $session_id[] = $s_id_exp[0];
            }
            $session_avg_time[] = $sesh_type[1];
        }
        array_multisort($session_id, $session_avg_time);
        $this->graph_data->session_duration = new stdClass();
        $this->graph_data->session_duration->labels = $session_id;
        $this->graph_data->session_duration->data = $session_avg_time;
    }

    public function students_requiring_training(): void {
        $this->graph_data->students_requiring_training = new stdClass();
        $this->graph_data->students_requiring_training->labels = array_keys($this->graph_stats['studentsRequireTng']);
        $this->graph_data->students_requiring_training->data = array_values($this->graph_stats['studentsRequireTng']);
    }

    public function sessions_per_month(): void {
        $this->graph_data->sessions_per_month = new stdClass();
        $this->graph_data->sessions_per_month->labels = array_keys($this->graph_stats['sessionsByType']);
        $this->graph_data->sessions_per_month->data = array_values($this->graph_stats['sessionsByType']);
        if (!is_array($this->graph_data->sessions_per_month->data)) {
            $this->graph_data->sessions_per_month->data = [];
        }
    }

    public function sessions_by_staff(): void {
        $instructors = $plot_array = [];
        $instructional_categories = ['S1', 'S2', 'S3', 'C1', 'Other'];
        foreach ($instructional_categories as $instructional_category) {
            $$instructional_category = [];
        }
        foreach ($this->graph_stats['trainerSessions'] as $instructor) {
            $instructors[] = $instructor['name'];
            foreach ($instructional_categories as $instructional_category) {
                $$instructional_category[] = $instructor[$instructional_category];
            }
        }
        foreach ($instructional_categories as $instructional_category) {
            $plot_item = new stdClass();
            $plot_item->label = $instructional_category;
            $plot_item->data = $$instructional_category;
            $plot_array[] = $plot_item;
        }
        $this->graph_data->sessions_by_staff = new stdClass();
        $this->graph_data->sessions_by_staff->labels = $instructors;
        $this->graph_data->sessions_by_staff->data = $plot_array;
    }
}
