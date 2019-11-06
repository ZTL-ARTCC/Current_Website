<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audits';
    protected $fillable = ['id', 'cid', 'ip', 'what', 'created_at', 'updated_at'];

    public function getTimeDateAttribute() {
        $date = $this->created_at;
        $new_date = $date->format('m/d/Y').' at '.substr($date, 11, 5);

        return $new_date;
    }
}
