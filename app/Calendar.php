<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendar';
    protected $fillable = ['id', 'title', 'date', 'time', 'body', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    
}
