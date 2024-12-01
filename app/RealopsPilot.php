<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RealopsPilot extends Authenticatable {
    protected $table = 'realops_pilots';

    public function getFullNameAttribute() {
        return $this->fname . ' ' . $this->lname;
    }

    public static function getPublicName($cid) {
        $p = RealopsPilot::find($cid);
        if(!$p) {
            return '';
        }
        switch ($p->privacy) {
            case 1: return $p->fname;
                break;
            case 2: return $cid;
                break;
            case 3:  return 'Anonymous';
            case 0:
            default: return $p->full_name;
        }
    }

    public function pilotPassportEnrollments(): HasMany {
        return $this->hasMany(PilotPassportEnrollment::class, 'cid');
    }
}
