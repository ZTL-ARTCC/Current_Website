<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scenery extends Model
{
    protected $table = 'scenery';
    protected $fillable = ['id', 'name', 'description', 'sim', 'link', 'created_at', 'updated_at'];
}
