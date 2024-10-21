<?php

namespace App;

use App\PilotPassportEnrollment;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RealopsPilot extends Authenticatable {
    protected $table = 'realops_pilots';

    public function getFullNameAttribute() {
        return $this->fname . ' ' . $this->lname;
    }

    public function pilotPassportEnrollments(): HasMany {
        return $this->hasMany(PilotPassportEnrollment::class, 'cid');
    }
}
