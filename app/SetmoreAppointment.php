<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetmoreAppointment extends Model {
    protected $table = 'setmore';
    protected $fillable = ['id', 'setmore_key', 'start_time', 'duration', 'staff_key', 'staff_name', 'service_key', 'service_description', 'customer_cid', 'updated_at'];
}
