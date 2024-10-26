@extends('layouts.email')

@section('content')
<p>Dear {{ $pilot->full_name }},</p>

<p>Congratulations on completing the vZTL Pilot Passport {{ $data->title }} Challenge!! You've earned a reward, visible at: 
    <a href="https://www.ztlartcc.org/pilot_passport" alt="Website">https://www.ztlartcc.org/pilot_passport</a>.</p>

<p>Your achievement will be displayed on the ZTL Website for all to see. You may adjust your privacy settings or disable this feature
    by visiting our website at the link above and selecting the "Settings" tab.
</p>

<p>If you did not enroll in this program or otherwise believe this email to be in error, please contact us at: 
    <a href="emailto:wm@ztlartcc.org" alt="Email">wm@ztlartcc.org</a>.</p>

<p>Thank you!<br> - vZTL Staff</p>
@endsection
