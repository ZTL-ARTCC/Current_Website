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
        if (count($row) < 5) {
            throw new Exception("Invalid row: too few entries (expected at least 5, got " . count($row) . ")");
        }
        $est_time_enroute = null;
        $route = null;

        if (count($row) > 5) {
            $est_time_enroute = $row[5];
        }

        if (count($row) > 6) {
            $route = $row[6];
        }

        $flight = new RealopsFlight;
        $flight->flight_date = $row[0];
        $flight->flight_number = $row[1];
        $flight->dep_time = $row[2];
        $flight->dep_airport = $row[3];
        $flight->arr_airport = $row[4];
        $flight->est_time_enroute = $est_time_enroute;
        $flight->route = $route;

        return $flight;
    }

    public function rules(): array {
        return [
            '0' => ['required', 'date_format:Y-m-d'],
            '1' => 'required',
            '2' => ['required', 'date_format:H:i'],
            '3' => 'required',
            '4' => 'required',
            '5' => ['date_format:H:i']
        ];
    }
}
