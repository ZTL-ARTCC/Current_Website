<?php

namespace App;

use App\Class\LatLng;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilotPassportAirfield extends Model {
    protected $table = "pilot_passport_airfield";

    public function fetchLatLng(): LatLng {
        return new LatLng($this->latitude, $this->longitude);
    }

    public function mapping(): BelongsTo {
        return $this->belongsTo(PilotPassportAirfieldMap::class, 'airfield', 'id');
    }

}
