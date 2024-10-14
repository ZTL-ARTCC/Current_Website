<?php

namespace App\Console\Commands;

use App\Mail\TrainingReminder;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
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
                ->select('customer_user.first_name AS first_name', 'customer_user.last_name AS last_name', 'customer_user.email AS email', 'customer_user.timezone AS customer_timezone', 'ea_appointments.start_datetime AS start_datetime', 'ea_appointments.id AS appointment_id', 'ea_appointments.notes AS appointment_notes', 'ea_services.name AS service_description', 'staff_user.first_name AS staff_first_name', 'staff_user.last_name AS staff_last_name', 'staff_user.timezone AS booking_timezone')
                ->where('ea_appointments.start_datetime', '<=', Carbon::now('America/New_York')->addHours(24)->format('Y-m-d H:i:s'))
                ->where('ea_appointments.start_datetime', '>', Carbon::now('America/New_York')->format('Y-m-d H:i:s'))
                ->where('ea_appointments.notes', 'not like', "%[Notified%")
                ->get();
            foreach ($ea_appointments as $ea_appointment) {
                $student_full_name = $ea_appointment->first_name . ' ' . $ea_appointment->last_name;
                $trainer_full_name = $ea_appointment->staff_first_name . ' ' . $ea_appointment->staff_last_name;
                $appt_start_datetime = Carbon::parse($ea_appointment->start_datetime, $ea_appointment->booking_timezone)->setTimezone($ea_appointment->customer_timezone);
                $ctz = new CarbonTimeZone($ea_appointment->customer_timezone);
                $appointment_date_time = $appt_start_datetime->format('m/d/Y') . ' ' . $appt_start_datetime->format('H:i') . ' ' . strtoupper($ctz->getAbbr());
                $this->info('Sending email reminder to ' . $student_full_name . ' for ' . $ea_appointment->service_description . ' with ' . $trainer_full_name . ' on ' . $appointment_date_time);

                Mail::to($ea_appointment->email)->send(
                    new TrainingReminder(
                        $student_full_name,
                        $ea_appointment->service_description,
                        $trainer_full_name,
                        $appointment_date_time,
                        $ea_appointment
                    )
                );

                $notes = (strlen($ea_appointment->appointment_notes) > 0) ?  $ea_appointment->appointment_notes . ' ' : '';
                $notes .= '[Notified ' . Carbon::now('UTC')->format('m-d-Y H:i:s') . 'Z]';
                DB::connection('ea_mysql')
                    ->table('ea_appointments')
                    ->where('id', $ea_appointment->appointment_id)
                    ->update(['notes'=>$notes]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->info('Error: ' . $e);
        }
    }
}
