<?php

namespace App\Exports;

use App\RealopsFlight;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RealopsExport implements FromCollection, WithHeadings, WithMapping {
    public function collection() {
        return RealopsFlight::all();
    }

    public function headings(): array {
        return [
            'Flight Number',
            'Callsign',
            'Flight Date',
            'Departure Time',
            'Point of Departure',
            'Point of Arrival',
            'Arrival Time',
            'Gate',
            'Pilot Name',
            'Pilot CID',
            'Pilot Email'
        ];
    }

    public function map($flight): array {
        $pilot = new \stdClass;
        $pilot->full_name = '';
        $pilot->email = '';
        if (!empty($flight->assigned_pilot)) {
            $pilot = $flight->assigned_pilot;
        }
        return [
            $flight->flight_number,
            $flight->callsign,
            $flight->flight_date,
            $flight->dep_time,
            $flight->dep_airport,
            $flight->arr_airport,
            $flight->est_time_enroute,
            $flight->gate,
            $pilot->full_name,
            $flight->assigned_pilot_id,
            $pilot->email
        ];
    }
}
