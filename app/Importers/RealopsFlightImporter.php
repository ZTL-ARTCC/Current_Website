<?php

namespace App\Importers;

use App\RealopsFlight;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class RealopsFlightImporter implements ToModel, WithValidation {
    /**
     * @throws Exception
     */
    public function model($row) {
        if (count($row) < 6) {
            throw new Exception("Invalid row: too few entries (expected at least 6, got " . count($row) . ")");
        }
        $est_time_enroute = null;
        $gate = null;

        if (count($row) > 6) {
            $est_time_enroute = $row[6];
        }

        if (count($row) > 7) {
            $gate = $row[7];
        }

        $flight = new RealopsFlight;
        $flight->flight_number = $row[0];
        $flight->callsign = $row[1];
        $flight->flight_date = $row[2];
        $flight->dep_time = $row[3];
        $flight->dep_airport = $row[4];
        $flight->arr_airport = $row[5];
        $flight->est_time_enroute = $est_time_enroute;
        $flight->gate = $gate;

        return $flight;
    }

    public function rules(): array {
        return [
            '0' => 'required',
            '2' => ['required', 'date_format:Y-m-d'],
            '3' => ['required', 'date_format:H:i'],
            '4' => 'required',
            '5' => 'required',
            '6' => ['date_format:H:i']
        ];
    }
}
