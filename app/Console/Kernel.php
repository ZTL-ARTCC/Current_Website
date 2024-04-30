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
        '\App\Console\Commands\VatsimAtcBookingSync',
        '\App\Console\Commands\VATUSAEventsUpdate',
        '\App\Console\Commands\UploadTrainingTickets',
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void {
        $schedule->command('cache:prune-stale-tags')->hourlyAt(3);
        $schedule->command('SoloCerts:UpdateSoloCerts')->dailyAt('05:01');
        $schedule->command('RosterUpdate:UpdateRoster')->hourlyAt(7);
        $schedule->command('Vatsim:AtcBookingSync')->hourlyAt(12);
        $schedule->command('VATUSAEvents:Update')->hourlyAt(22);
        $schedule->command('VATUSATrainingTickets:UploadPending')->hourlyAt(33);
        $schedule->command('Overflights:GetOverflights')->everyFourMinutes();
        $schedule->command('Weather:UpdateWeather')->everyFiveMinutes();
        $schedule->command('OnlineControllers:GetControllers')->everyMinute();
        if (FeatureToggle::isEnabled("auto_support_events")) {
            $schedule->command('Events:UpdateSupportEvents')->daily()->dailyAt('05:09');
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
