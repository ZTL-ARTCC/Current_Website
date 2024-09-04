<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventStat extends Model {
    protected $table = 'event_stats';

    protected function casts() {
        return [
            'controllers_by_rating' => 'array',
            'movements' => 'array',
            'peak_aar' => 'array',
            'peak_adr' => 'array',
            'peak_delays' => 'array'
        ];
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}
