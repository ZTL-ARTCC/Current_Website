<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingSortCategory extends Model {
    protected $table = 'training_sort_category';
    protected $fillable = [
        'id',
        'category_name',
        'associated_rating',
        'scheddy_booking_map'
    ];
    public $timestamps = false;
}
