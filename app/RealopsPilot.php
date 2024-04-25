<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class RealopsPilot extends Authenticatable {
    protected $table = 'realops_pilots';

    public function getFullNameAttribute() {
        return $this->fname . ' ' . $this->lname;
    }

}
