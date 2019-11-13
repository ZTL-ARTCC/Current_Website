<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OverflightUpdate extends Model
{
    protected $table = 'flights_within_artcc_updates';
    protected $fillable = ['id', 'created_at', 'updated_at'];
}
