@extends('layouts.email')

@section('content')
<p>Dear {{ $pilot->full_name }},</p>

<p>Thank you for enrolling in the vZTL Pilot Passport {{ $data->title }} Challenge! You can view program requirements and track your progress on
    our website at <a href="https://www.ztlartcc.org/pilot_passport" alt="Website">https://www.ztlartcc.org/pilot_passport</a>.</p>

<p>Please remember that in order to get credit for visiting a participating airfield, you should plan to land and remain on the ground for 
    about 5 minutes. You can immediately verify that we logged your visit by refreshing your passport book on our website or by monitoring
    your email (we will send a confirmation email with each qualifying visit).</p>

<p>If you did not enroll in this program or otherwise believe this email to be in error, please contact us at: 
    <a href="emailto:wm@ztlartcc.org" alt="Email">wm@ztlartcc.org</a>.</p>

<p>Thank you!<br> - vZTL Staff</p>
@endsection
