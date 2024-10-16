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
        '\App\Console\Commands\EventEmails',
        '\App\Console\Commands\EventStatReport',
        '\App\Console\Commands\ARTCCOverflights',
        '\App\Console\Commands\RosterRemovalWarn',
        '\App\Console\Commands\VatsimAtcBookingSync',
        '\App\Console\Commands\VATUSAEventsUpdate',
        '\App\Console\Commands\UploadTrainingTickets',
        '\App\Console\Commands\TrainingReminderEmails'
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void {
        $schedule->command('SoloCerts:UpdateSoloCerts')->dailyAt('05:01')->monitorName('VATUSA Solo Cert Sync');
        $schedule->command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])->dailyAt('05:17')->monitorName('Prune Task Monitor Database');
        $schedule->command('Events:GenerateStatReports')->dailyAt('05:42')->monitorName('Event Stat Reports');
        $schedule->command('Training:SendReminderEmails')->hourlyAt(3)->monitorName('Send Training Session Reminder Emails');
        $schedule->command('RosterUpdate:UpdateRoster')->hourlyAt(7)->monitorName('Roster Update');
        $schedule->command('Vatsim:AtcBookingSync')->hourlyAt(12)->monitorName('VATSIM ATC Booking Sync');
        $schedule->command('VATUSAEvents:Update')->hourlyAt(22)->monitorName('VATUSA Events Sync');
        $schedule->command('VATUSATrainingTickets:UploadPending')->hourlyAt(33)->monitorName('VATUSA Training Ticket Sync');
        $schedule->command('queue:work --max-jobs=7 --stop-when-empty')->everyFiveMinutes()->monitorName('Queue Processing');
        $schedule->command('Weather:UpdateWeather')->everyFourMinutes()->monitorName('Update Weather');
        $schedule->command('Overflights:GetOverflights')->everyThreeMinutes()->monitorName('Sync Overflights');
        $schedule->command('OnlineControllers:GetControllers')->everyMinute()->monitorName('Get Online Controllers');
        $schedule->command('Events:UpdateSupportEvents')->dailyAt('05:09')->monitorName('Sync Support Events')->when(function () {
            return FeatureToggle::isEnabled('auto_support_events');
        });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
