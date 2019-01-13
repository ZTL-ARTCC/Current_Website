<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audits';
    protected $fillable = ['id', 'cid', 'ip', 'what', 'created_at', 'updated_at']
}
