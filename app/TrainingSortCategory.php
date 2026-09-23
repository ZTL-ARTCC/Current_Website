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

    public static function legacyTicketTypes(int $category): int {
        if (config('vatusa.facility') != 'ZTL') {
            return self::first()->id;
        }
        return match ($category) {
            11  => 104,
            18  => 108,
            27  => 113,
            31  => 115,
            32  => 115,
            42  => 121,
            103 => 104,
            107 => 108,
            112 => 113,
            114 => 115,
            120 => 121,
            default => $category,
        };
    }
}
