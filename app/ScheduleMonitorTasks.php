<?php

namespace App;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\ScheduleMonitor\Support\ScheduledTasks\ScheduledTasks;

class ScheduleMonitorTasks extends Model {
    public static function getTasks(): Collection {
        app(Kernel::class)->bootstrap();
        return ScheduledTasks::createForSchedule()->monitoredTasks();
    }
}
