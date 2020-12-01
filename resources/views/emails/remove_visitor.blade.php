@extends('layouts.email')

@section('content')
<p>Dear {{ $user->full_name }},</p>
<br>
<p>This email is to inform you that you have been removed from the ZTL Visiting Roster. The causes for removal can be found in ZTL ARTCC Facility Administrative Policy 7230.1B Chapter 3-5.</p>
<p>If you are still unsure of the reason for your removal, please contact the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p>
<br>
<p>Sincerely,</p>
<p>vZTL ARTCC Staff</p>
@endsection
