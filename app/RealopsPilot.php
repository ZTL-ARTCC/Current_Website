<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RealopsPilot extends Authenticatable {
    protected $table = 'realops_pilots';

    public function getFullNameAttribute() {
        return $this->fname . ' ' . $this->lname;
    }

    public function pilotPassportEnrollments(): HasMany {
        return $this->hasMany(PilotPassportEnrollment::class, 'cid');
    }
}
