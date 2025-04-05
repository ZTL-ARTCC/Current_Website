@extends('layouts.email')

@section('content')
<p>Dear {{ $trainer_name }},</p>

<p>One of your students has entered a comment on their training ticket. You can view the comment here: <a href="{{url('/')}}/dashboard/training/tickets/view/{{ $ticket_id }}">Training Ticket</a>.</p>
<br>

<p>If you have any thoughts/comments, please email the TA at <a href="mailto:ta@ztlartcc.org">ta@ztlartcc.org</a>.</p>
<br>

<p>Sincerely,</p>
<p>ZTL ARTCC Training Team</p>
@endsection
