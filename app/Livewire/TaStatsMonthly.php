<?php

namespace App\Livewire;

use App\Http\Controllers\TrainingDash;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class TaStatsMonthly extends Component {
    public $stats;
    public $date_select;
    public $yearOfMonthsLookback = [];

    public function render() {
        $yearSel = $monthSel = null;
        $datePart = explode(' ', $this->date_select);
        $monthSel = Carbon::now()->format('m');
        $yearSel = Carbon::now()->format('Y');
        if (count($datePart) == 2) {
            $monthSel = $datePart[0];
            $yearSel = $datePart[1];
        }
        $this->stats = TrainingDash::generateTrainingStats($yearSel, $monthSel, 'stats');
        $this->lookback_months();
        return view('livewire.ta-stats-monthly');
    }

    public function lookback_months(): void {
        $lookback_dates = array_reverse(CarbonPeriod::create(now()->subMonths(11), '1 month', now())->toArray());
        foreach($lookback_dates as $date) {
            $this->yearOfMonthsLookback[$date->format('m Y')] = $date->format('M Y');
        }
    }
}
