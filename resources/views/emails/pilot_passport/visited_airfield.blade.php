@extends('layouts.email')

@section('content')
<p>Dear {{ $p->full_name }},</p>

<p>Congratulations on your visit to {{ $data->name }}! You've earned a stamp in your pilot passport book, visible at: 
    <a href="https://www.ztlartcc.org/pilot_passport" alt="Website">https://www.ztlartcc.org/pilot_passport</a>.</p>

@if(!is_null($data->description))
    <p>{{ $data->description }}</p>

@endif
<p>If you did not enroll in this program or otherwise believe this email to be in error, please contact us at: 
    <a href="emailto:wm@ztlartcc.org" alt="Email">wm@ztlartcc.org</a>.</p>

<p>Thank you!<br> - vZTL Staff</p>
@endsection
