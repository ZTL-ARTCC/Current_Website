@extends('layouts.email')

@section('content')
<p>Dear {{ $s->full_name }},</p>
<br>
<p>You have not met the activity requirements within the last thirty days. As a certified controller, you are required to control, on the network, for at least 60 minutes every 30 days. You will have a 30 day grace period to meet this requirement, but after that you may be removed from the roster.</p>
<p>If you have any questions or are on an LOA, please contact the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p>
<br>
<p>Sincerely,</p>
<p>vZTL ARTCC Staff</p>
@endsection
