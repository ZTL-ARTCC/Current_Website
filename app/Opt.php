<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opt extends Model
{
    protected $table = 'gdpr_compliance';
    protected $fillable = ['id', 'option', 'ip_address', 'means', 'created_at', 'updated_at'];
}
