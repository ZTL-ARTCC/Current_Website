<?php

namespace App\Jobs;

use App\Http\Controllers\DiscordController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AssignEventRole implements ShouldQueue {
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $event_id) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $discord = new DiscordController;
        $discord->enableEventRole($this->event_id);
    }
}
