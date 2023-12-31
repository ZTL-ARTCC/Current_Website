<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetmoreAppointment extends Model {
    protected $table = 'setmore';
    protected $fillable = ['created_at'];
    public $timestamps = false;
}
