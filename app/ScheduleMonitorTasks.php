<?php

namespace App;

use App\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\ScheduleMonitor\Support\ScheduledTasks\ScheduledTasks;

class ScheduleMonitorTasks extends Model {
    public static function getTasks(): Collection {
        app()->make(Kernel::class);
        return ScheduledTasks::createForSchedule()->monitoredTasks();
    }
}
