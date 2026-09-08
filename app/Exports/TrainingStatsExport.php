<?php

namespace App\Exports;

use App\Exports\Sheets\TrainingMonthlyExport;
use App\Exports\Sheets\TrainingStaffExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TrainingStatsExport implements WithMultipleSheets {

    use Exportable;

    public function __construct(private string $date_select) {

    }

    public function sheets(): array {
        return [
            new TrainingStaffExport($this->date_select),
            new TrainingMonthlyExport($this->date_select),
        ];
    }
}
