<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Mail;

class TrainingReminderEmails extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Training:SendReminderEmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends training session reminder emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $ea_appointments = [];
            $ea_appointments = DB::connection('ea_mysql')
                ->table('ea_appointments')
                ->join('ea_services', 'ea_appointments.id_services', '=', 'ea_services.id')
                ->join('ea_users AS customer_user', 'ea_appointments.id_users_customer', '=', 'customer_user.id')
                ->join('ea_users AS staff_user', 'ea_appointments.id_users_provider', '=', 'staff_user.id')
                ->select('customer_user.first_name AS first_name', 'customer_user.last_name AS last_name', 'customer_user.email AS email', 'ea_appointments.start_datetime AS start_datetime', 'ea_services.name AS service_description', 'staff_user.first_name AS staff_first_name', 'staff_user.last_name AS staff_last_name')
                ->where('ea_appointments.start_datetime', '>', Carbon::now('America/New_York')->format('Y-m-d H:i:s'))
//                ->where('ea_appointments.start_datetime', '<', Carbon::now('America/New_York')->subHours(1)->format('Y-m-d H:i:s'))
                ->get();
            foreach ($ea_appointments as $ea_appointment) {
                $this->info('Sending email reminder to ' . $ea_appointment->first_name . ' ' . $ea_appointment->last_name . ' for ' . $ea_appointment->service_description . ' with ' . $ea_appointment->staff_first_name . ' ' . $ea_appointment->staff_last_name . ' on ' . Carbon::parse($ea_appointment->start_datetime)->format('m/d/Y') . ' ' . Carbon::parse($ea_appointment->start_datetime)->format('H:i') . ' ET');
                Mail::send('emails.training_reminder', ['trainee_name' => $ea_appointment->first_name . ' ' . $ea_appointment->last_name, 'session_type' => $ea_appointment->service_description, 'mentor_name' => $ea_appointment->staff_first_name . ' ' . $ea_appointment->staff_last_name, 'session_date_time' => Carbon::parse($ea_appointment->start_datetime)->format('m/d/Y') . ' ' . Carbon::parse($ea_appointment->start_datetime)->format('H:i') . ' ET'], function ($message) use ($ea_appointment) {
                    $message->from('training@notams.ztlartcc.org', 'vZTL ARTCC Training Department')->subject('Reminder: ' . $ea_appointment->service_description . ' with ' . $ea_appointment->staff_first_name . ' ' . $ea_appointment->staff_last_name . ' on ' . Carbon::parse($ea_appointment->start_datetime)->format('m/d/Y') . ' ' . Carbon::parse($ea_appointment->start_datetime)->format('H:i') . ' ET');
                    $message->to($ea_appointment->email);
                });
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->info('Error: ' . $e);
        }
    }
}
