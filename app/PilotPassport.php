<?php

namespace App;

use App\PilotPassportEnrollment;
use App\PilotPassportAirfieldMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PilotPassport extends Model {
    protected $table = "pilot_passport";

    /*
     * Validates that $airfield is included in a challenge that the pilot is enrolled in
    */
    public function airfieldInPilotChallenge($airfield, $cid): bool { 
        $enrollments = PilotPassportEnrollment::where('id', $cid)->get();
        if ($enrollments->isEmpty()) {
            return false;            
        }
        foreach ($enrollments as $e) {
            if (PilotPassportAirfieldMap::where('airfield', $airfield)->where('mapped_to', $e->id)->isNotEmpty()) {
                return true;
            }
        }
        return false;
    }

    public function airfields(): HasMany {
        return $this->HasMany(PilotPassportAirfieldMap::class, 'mapped_to');
    }
}
