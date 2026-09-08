<?php

namespace App\Exports\Sheets;

use App\Http\Controllers\TrainingDash;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class TrainingStaffExport implements FromCollection, WithHeadings, WithStrictNullComparison, WithTitle {
    private array $stats;

    public function __construct(private string $date_select) {
        $date_part = explode(' ', $this->date_select);
        $this->stats = TrainingDash::generateTrainingStats($date_part[1], $date_part[0], 'stats');
    }

    public function title(): string {
        $date_part = explode(' ', $this->date_select);
        return $date_part[1];
    }

    public function collection() {
        $trainer_stats = [];
        foreach ($this->stats['trainerSessions'] as $trainer) {
            $trainer_stats[] = [$trainer['name'], '', $trainer['total']];
        }
        return new Collection($trainer_stats);
    }

    public function headings(): array {
        $date_part = explode(' ', $this->date_select);
        $month_name = Carbon::create(null, $date_part[0], 1)->format('M');
        return [
            ' ',
            ' ',
            ' ' . $month_name . ' ' . $date_part[1]
        ];
    }
}
