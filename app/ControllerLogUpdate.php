<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControllerLogUpdate extends Model
{
    protected $table = 'controller_log_update';
	protected $fillable = ['id', 'created_at', 'updated_at'];
}
