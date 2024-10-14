@extends('layouts.email')

@section('content')
<p>Dear {{ $trainee_name }},</p>

<p>This email is to remind you about your {{ $session_type }} appointment with {{ $trainer_full_name }} on {{ $appointment_date_time }}. As it is now within 24 hours, please contact your mentor directly to cancel or reschedule, or you may be marked a no-show.</p>
<br>

<p>This email is automated. If you believe you have received this email in error, please contact the ZTL Webmaster at <a href="mailto:wm@ztlartcc.org">wm@ztlartcc.org</a>.</p>
<br>

<p>Sincerely,</p>
<p>ZTL ARTCC Staff</p>
@endsection
