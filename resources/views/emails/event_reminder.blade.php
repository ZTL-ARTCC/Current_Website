@extends('layouts.email')

@section('content')
<p>Dear {{ $user->full_name }},</p>

<p>This is a reminder that you have signed up for {{ $event->name }} on {{ $event->date }}. The event begins at {{ $event->start_time }}z and you should be present at least 30 minutes prior to this start time for the controller briefing.</p>
<br>

@if($positions->count() > 0)
    <p><b>Position Assignments (Tentative):</p></b>
    <ul>
        @foreach($positions as $p)
            <li>
                {{ $p->controller_name }} - {{ $p->position_name }}
                <i>
                    @if($p->start_time != null)
                        ({{ $p->start_time }}
                    @else
                        ({{ $event->start_time }}
                    @endif
                    -
                    @if($p->end_time != null)
                        {{ $p->end_time }}z)
                    @else
                        {{ $event->end_time }}z)
                    @endif
                </i>
            </li>
        @endforeach
    </ul>
    <p>Please note that positions can change at any time and the official positions will be assigned at the controller brief 30 minutes prior to the event.</p>
@endif

<br>
<p>If you have any questions or concerns, please contact the events coordinator at <a href="mailto:ec@ztlartcc.org">ec@ztlartcc.org</a>.</p>
<br>

<p>Sincerely,</p>
<p>ZTL ARTCC Staff</p>
@endsection
