<?php

namespace App\Livewire;

use App\Http\Controllers\TrainingDash;
use Carbon\Carbon;
use Livewire\Component;

class TaStatsMonthly extends Component {
    public $stats;
    public $date_select;

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
        return view('livewire.ta-stats-monthly');
    }
}
