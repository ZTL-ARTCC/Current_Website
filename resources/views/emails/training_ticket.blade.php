@extends('layouts.email')

@section('content')
<p>Dear {{ $controller->full_name }},</p>

<p>A new training ticket has been submitted for you by {{ $trainer->full_name }}:</p>

<ul>
    <li><b>Progress:</b> {{ $ticket->type_name }}</li>
    <li><b>Position:</b> {{ $ticket->position_name }}</li>
    <li><b>Date and Time:</b> {{ $ticket->date }} from {{ $ticket->start_time }} to {{ $ticket->end_time }} ET</li>
	<li><b>Duration:</b> {{ $ticket->duration }}</li>
	<li><b>Score (1 = Poor, 5 = Excellent):</b> 
    @if($ticket->score) 
        <div id="stars"> 
            @for($i = 0; $i < 5; $i++)
                <span>{!! $i<$ticket->score ? '&#9733;' : '&#9734;' !!}</span>
            @endfor
        </div>
    @else 
        N/A 
    @endif</li>
	<li><b>Movements:</b> @if($ticket->movements) {{ $ticket->movements }} @else N/A @endif</li>
    <li><b>Comments:</b> <br>
        {!! nl2br($ticket->comments) !!}
    </li>
</ul>

<p>If you would like to submit comments to be included in your training ticket, you can do that by going to <a href="https://www.ztlartcc.org/dashboard/controllers/ticket/{{ $ticket->id }}">https://www.ztlartcc.org/dashboard/controllers/ticket/{{ $ticket->id }}</a> or by viewing the ticket from your profile page on the controller dashboard. Please note that these comments are viewable by all the training staff and once they are submitted, they cannot be changed.</p>

<p>If you believe there are any errors, please contact the training administrator at <a href="mailto:ta@ztlartcc.org">ta@ztlartcc.org</a>.</p>

<p>Best regards,</p>
<p>ZTL Training Staff</p>
@endsection
