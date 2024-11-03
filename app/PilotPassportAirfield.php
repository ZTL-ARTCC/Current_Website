<?php

namespace App;

use App\Class\LatLong;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilotPassportAirfield extends Model {
    protected $table = "pilot_passport_airfield";
    protected $casts = ['id' => 'string'];
    public $incrementing = false;

    public function fetchLatLong(): LatLong {
        return new LatLong($this->latitude, $this->longitude);
    }

    public function mapping(): BelongsTo {
        return $this->belongsTo(PilotPassportAirfieldMap::class, 'airfield', 'id');
    }

}
