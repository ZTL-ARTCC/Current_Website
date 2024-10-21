<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PilotPassportAirfieldMap extends Model {
    protected $table = "pilot_passport_airfield_map";

    public function airfield(): HasOne
    {
        return $this->hasOne(PilotPassportAirfield::class, 'id', 'airfield');
    }
}
