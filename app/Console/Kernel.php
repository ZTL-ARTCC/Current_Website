<?php

namespace App\Console;

use App\FeatureToggle;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\FetchMetar',
        '\App\Console\Commands\OnlineControllerUpdate',
        '\App\Console\Commands\RosterUpdate',
        '\App\Console\Commands\VisitAgreement',
        '\App\Console\Commands\EventEmails',
        '\App\Console\Commands\ARTCCOverflights',
        '\App\Console\Commands\RosterRemovalWarn',
        '\App\Console\Commands\VATUSAEventsUpdate',
        '\App\Console\Commands\SetmoreAppointments',
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void {
        $schedule->command('cache:prune-stale-tags')->hourly();
        $schedule->command('SoloCerts:UpdateSoloCerts')->daily();
        $schedule->command('RosterUpdate:UpdateRoster')->hourly();
        $schedule->command('VATUSAEvents:Update')->hourly();
        $schedule->command('Overflights:GetOverflights')->everyFiveMinutes();
        $schedule->command('Weather:UpdateWeather')->everyFiveMinutes();
        if (FeatureToggle::isEnabled('online_controller_debug')) {
            $schedule->command('OnlineControllers:GetControllers')->everyMinute()->appendOutputTo('storage/logs/online-controllers.log')->emailOutputOnFailure('wm@ztlartcc.org');
        } else {
            $schedule->command('OnlineControllers:GetControllers')->everyMinute();
        }
        $schedule->command('SetmoreAppointments:Update')->everyThirtyMinutes();
        if (FeatureToggle::isEnabled("auto_support_events")) {
            $schedule->command('Events:UpdateSupportEvents')->daily();
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
