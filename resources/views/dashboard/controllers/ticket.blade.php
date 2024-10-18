@extends('layouts.dashboard')

@section('title')
View Training Ticket
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/trainingticket.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'View Training Ticket for ' .  $ticket->controller_name])

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
	        <p><b>Score:</b> 
            @if($ticket->score) 
                <div id="stars"> 
                    @for($i = 0; $i < 5; $i++)
                        <span>{!! $i<$ticket->score ? html_entity_decode('&starf;') : html_entity_decode('&star;') !!}</span>
                    @endfor
                </div>
            @else 
                N/A 
            @endif
            </p>
            <p><b>Movements:</b> @if($ticket->movements) {{ $ticket->movements }} @else N/A @endif</p>
            <p><b>Comments:</b></p>
            @if($ticket->comments != null)
                <p>{!! nl2br($ticket->comments) !!}</p>
            @else
                <p>No comments for this ticket.</p>
            @endif
            <p><b>Your Comments:</b></p>
            @if($ticket->student_comments != null)
                <p>{{ $ticket->student_comments }}</p>
            @else
                {{ html()->form()->route('addStudentComments', [$ticket->id])->open() }}
                    <div class="form-group">
                        {{ html()->textarea('student_comments', null)->placeholder('Enter your comments here. Please note these can be seen by all training staff and yourself. Once these are submitted, they cannot be changed')->class(['form-control']) }}
                    </div>
                    <button class="btn btn-success" action="submit">Save Comments</button>
                {{ html()->form()->close() }}
            @endif
        </div>
    </div>
</div>
@endsection
