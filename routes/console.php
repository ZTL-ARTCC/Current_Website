<?php

use App\Enums\FeatureToggles;
use App\Enums\Queues;
use Illuminate\Support\Facades\Schedule;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

Schedule::command('SoloCerts:UpdateSoloCerts')->dailyAt('05:01');
Schedule::command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])->dailyAt('05:17')->monitorName('Prune Task Monitor Database');
Schedule::command('Events:GenerateStatReports')->dailyAt('05:42')->monitorName('Event Stat Reports');
Schedule::command('RosterUpdate:UpdateRoster')->hourlyAt(7)->monitorName('Roster Update');
Schedule::command('Vatsim:AtcBookingSync')->hourlyAt(12)->monitorName('VATSIM ATC Booking Sync');
Schedule::command('VATUSAEvents:Update')->hourlyAt(22)->monitorName('VATUSA Events Sync');
Schedule::command('VATUSATrainingTickets:UploadPending')->hourlyAt(33)->monitorName('VATUSA Training Ticket Sync');
Schedule::command('queue:work --queue=' . Queues::MAIL->value . ' --max-jobs=7 --stop-when-empty')->everyFiveMinutes()->monitorName('Email Queue Processing');
Schedule::command('Weather:UpdateWeather')->everyFourMinutes()->monitorName('Update Weather');
Schedule::command('Overflights:GetOverflights')->everyThreeMinutes()->monitorName('Sync Overflights');
Schedule::command('OnlineControllers:GetControllers')->everyMinute()->monitorName('Get Online Controllers');
Schedule::command('RosterUpdate:UpdateAcademyExams')->cron('17 */2 * * *')->monitorName('Active Controller Exam Update');
Schedule::command('Events:UpdateSupportEvents')->dailyAt('05:09')->monitorName('Sync Support Events')->when(function () {
    return toggleEnabled(FeatureToggles::AUTO_SUPPORT_EVENTS);
});
Schedule::command('PilotPassport:ActivityUpdate')->everyFiveMinutes()->monitorName('Update Pilot Passport Activity');
Schedule::command('app:moodle-sync')->twiceDailyAt(2, 14, 27)->environments(['production'])->monitorName('Sync Moodle User Table');
