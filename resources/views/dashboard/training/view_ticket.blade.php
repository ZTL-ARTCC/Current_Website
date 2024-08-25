@extends('layouts.dashboard')

@section('title')
View Training Ticket
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ asset('css/trainingticket.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'View Training Ticket for ' . $ticket->controller_name])

<div class="container">
    <a class="btn btn-primary" href="/dashboard/training/tickets?id={{ $ticket->controller_id }}"><i class="fas fa-arrow-left"></i> Back</a>
    @if(Auth::id() == $ticket->trainer_id || Auth::user()->isAbleTo('snrStaff'))
        <a class="btn btn-warning" href="/dashboard/training/tickets/edit/{{ $ticket->id }}">Edit Ticket</a>
    @endif
    @if(Auth::user()->isAbleTo('snrStaff'))
        <a class="btn btn-danger" href="/dashboard/training/tickets/delete/{{ $ticket->id }}">Delete Ticket</a>
    @endif
    <br><br>
    <div class="card">
        <div class="card-header">
            <h3>Training Ticket for {{ $ticket->controller_name }} on {{ $ticket->position_name }}</h3>
        </div>
        <div class="card-body">
            <div class="float-right">
                @if($ticket->is_vatusa_synced)
                    <span class="badge badge-success">Synced with VATUSA</span>
                @else
                    <span class="badge badge-danger">Pending Sync with VATUSA</span>
                @endif
            </div>
            <p><b>Trainer Name:</b> {{ $ticket->trainer_name }}</p>
            <p><b>Session Name/Type:</b> {{ $ticket->type_name }} on {{ $ticket->position_name }}</p>
            <p><b>Session Date:</b> {{ $ticket->date }}</p>
            <p><b>Start Time:</b> {{ $ticket->start_time }} ET</p>
            <p><b>End Time:</b> {{ $ticket->end_time }} ET</p>
            <p><b>Duration:</b> {{ $ticket->duration }}</p>
	        <p><b>Score:</b> 
                @if($ticket->score) 
                    <div id="stars" data-rating="{{ $ticket->score }}"> 
                        <span>&star;</span>
                        <span>&star;</span>
                        <span>&star;</span>
                        <span>&star;</span>
                        <span>&star;</span>
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
            <p><b>Trainer Comments:</b></p>
            @if($ticket->ins_comments != null)
                <p>{!! nl2br($ticket->ins_comments) !!}</p>
            @else
                <p>No trainer comments for this ticket.</p>
            @endif
        </div>
    </div>
</div>
<script src="{{asset('js/trainingticket_view.js')}}"></script>
@endsection
