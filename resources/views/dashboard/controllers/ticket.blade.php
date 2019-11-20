@extends('layouts.dashboard')

@section('title')
View Training Ticket
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>View Training Ticket for {{ $ticket->controller_name }}</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <a class="btn btn-primary" href="/dashboard/controllers/profile"><i class="fas fa-arrow-left"></i> Back</a>
    <br><br>
    <div class="card">
        <div class="card-header">
            <h3>Training Ticket for {{ $ticket->controller_name }} on {{ $ticket->position_name }}</h3>
        </div>
        <div class="card-body">
            <p><b>Trainer Name:</b> {{ $ticket->trainer_name }}</p>
            <p><b>Session Name/Type:</b> {{ $ticket->type_name }} on {{ $ticket->position_name }}</p>
            <p><b>Session Date:</b> {{ $ticket->date }}</p>
            <p><b>Start Time:</b> {{ $ticket->start_time }}</p>
            <p><b>End Time:</b> {{ $ticket->end_time }}</p>
            <p><b>Duration:</b> {{ $ticket->duration }}</p>
            <p><b>Comments:</b></p>
            @if($ticket->comments != null)
                <p>{!! nl2br(e($ticket->comments)) !!}</p>
            @else
                <p>No comments for this ticket.</p>
            @endif
        </div>
    </div>
</div>

@endsection
