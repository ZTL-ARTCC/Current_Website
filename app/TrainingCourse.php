<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model {
    protected $fillable = [
        'course_id',
        'course_name',
        'certification',
        'soi_link',
        'associated_rating'
    ];
    public $timestamps = false;
}
