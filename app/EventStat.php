<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventStat extends Model
{
    protected $table = 'event_stats';

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}
