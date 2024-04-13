<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AtcBooking extends Model {
    protected $table = 'atc_bookings';

    public const TYPES = [
        "BOOKING" => "booking",
        "EVENT" => "event",
        "EXAM" => "exam",
        "MONITORING" => "mentoring"
    ];

    public const DIVISION = "USA";

    public const SUBDIVISION = "ZTL";

    public function getStartDateFormattedAttribute() {
        return Carbon::create($this->start)->format('l, M jS');
    }

    public function getStartTimeFormattedAttribute() {
        return Carbon::create($this->start)->format("H:i");
    }

    public function getEndTimeFormattedAttribute() {
        return Carbon::create($this->end)->format("H:i");
    }
}
