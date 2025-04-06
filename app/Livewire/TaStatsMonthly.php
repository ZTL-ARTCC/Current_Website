<?php

namespace App\Livewire;

use App\Http\Controllers\TrainingDash;
use Illuminate\Http\Request;
use Livewire\Component;

class TaStatsMonthly extends Component
{
    public $stats;

    public function render(Request $request)
    {
        $yearSel = $monthSel = null;
        if (isset($request->date_select)) {
            $datePart = explode(' ', $request->date_select); // Format: MM YYYY
            $monthSel = $datePart[0];
            $yearSel = $datePart[1];
        }
        $this->stats = TrainingDash::generateTrainingStats($yearSel, $monthSel, 'stats');
        return view('livewire.ta-stats-monthly');
    }
}
