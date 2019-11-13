<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visit_requests';
    protected $fillable = ['id', 'cid', 'name', 'email', 'rating', 'home', 'reason', 'status', 'updated_by', 'created_at', 'updated_at'];

    public static $RatingShort = [
        0 => 'N/A',
        1 => 'OBS', 2 => 'S1', 
        3 => 'S2', 4 => 'S3', 
        5 => 'C1', 7 => 'C3', 
        8 => 'I1', 10 => 'I3', 
        11 => 'SUP', 12 => 'ADM',
    ];

    public function getRatingShortAttribute() {
        foreach (Visitor::$RatingShort as $id => $Short) {
            if ($this->rating == $id) {
                return $Short;
            }
        }

        return "";
    }
}
