<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PilotPassport extends Model {
    protected $table = "pilot_passport";

    public static function airfieldInPilotChallenge($airfield, $cid): bool {
        $enrollments = PilotPassportEnrollment::where('cid', $cid)->get();
        if ($enrollments->isEmpty()) {
            return false;
        }
        foreach ($enrollments as $e) {
            if (!PilotPassportAirfieldMap::where('airfield', $airfield)->where('mapped_to', $e->challenge_id)->get()->isEmpty()) {
                return true;
            }
        }
        return false;
    }

    public function airfields(): HasMany {
        return $this->HasMany(PilotPassportAirfieldMap::class, 'mapped_to');
    }
}
