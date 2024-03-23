<?php

namespace App;

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
}
