@extends('layouts.email')

@section('content')
<p>Dear {{ $controller->full_name }},</p>

<p>A new training ticket has been submitted for you by {{ $trainer->full_name }}:</p>

<ul>
    <li><b>Session Type:</b> {{ $ticket->type_name }}</li>
    <li><b>Position:</b> {{ $ticket->position_name }}</li>
    <li><b>Date and Time:</b> {{ $ticket->date }} from {{ $ticket->time_start }}z to {{ $ticket->time_end }}z</li>
	<li><b>Duration:</b> {{ $ticket->duration }}</li>
    <li><b>Comments:</b> <br>
        {!! nl2br(e($ticket->comments)) !!}
    </li>
</ul>

<p>If you believe there are any errors, please contact the training administrator at <a href="mailto:ta@ztlartcc.org">ta@ztlartcc.org</a>.</p>

<p>Best regards,</p>
<p>ZTL Training Staff</p>
@endsection
