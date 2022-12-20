@extends('layouts.email')

@section('content')
<p>Dear {{ $pilot->full_name }},</p>

<p>This is an automated email to inform you that Realops flight {{ $flight->flight_number }} has been cancelled. If you believe this is a mistake, you can rebid for the flight on the <a href="https://www.ztlartcc.org/realops">Realops Dashboard</a> if bidding is still open, or you can send an email to <a href="mailto:ec@ztlartcc.org">ec@ztlartcc.org</a>.</p>
@endsection
