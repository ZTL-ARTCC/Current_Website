<?php

namespace App\Exports\Sheets;

use App\TrainingTicket;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class TrainingMonthlyExport implements FromArray, WithHeadings, WithStrictNullComparison, WithTitle {

    private array $stats;

    public function __construct(private string $date_select) {
        $this->generate_stats();
    }

    public function title(): string {
        return 'Master Notes';
    }

    public function headings(): array {
        return [
            'Sessions by Type',
            'Unr. CD/GND',
            'Unr. TWR',
            'CLT CAB',
            'ATL ATCT',
            'Unr. APCH',
            'CLT TRACON',
            'A80',
            'ZTL',
            'Total'
        ];
    }

    public function array(): array {
        return [
            $this->stats
        ];
    }

    private function generate_stats(): void {
        $date_part = explode(' ', $this->date_select);
        $this->stats[] = Carbon::create(null, $date_part[0], 1)->format('F') . ' ' . substr($date_part[1], -2);
        $start_date = Carbon::createFromFormat('m/d/Y', $date_part[0] . '/01/' . $date_part[1]);
        $start_of_month = $start_date->format('Y-m-d');
        $end_of_month = $start_date->endofMonth()->format('Y-m-d');
        $tickets = TrainingTicket::whereBetween('start_date', [$start_of_month, $end_of_month])->get();
        // Unrestricted CD/GND
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['S1'])->count();
        // Unrestricted TWR
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['S2'])->count();
        // CLT CAB
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['CLT_ATCT'])->count();
        // ATL ATCT
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['ATL_ATCT'])->count();
        // Unrestricted APCH
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['S3'])->count();
        // CLT TRACON
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['CLT_APP'])->count();
        // A80
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['A80'])->count();
        // ZTL
        $this->stats[] = $tickets->whereIn('session_id', TrainingTicket::$session_ids_by_category['C1'])->count();
        // Total
        $this->stats[] = $tickets->count();
    }
}
