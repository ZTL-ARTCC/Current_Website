@extends('layouts.dashboard')

@section('title')
Edit Training Ticket
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Training Ticket'])

<div class="container">
    {{ html()->form()->route('saveTicket', [$ticket->id])->open() }}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="controller" class="form-label">Controller</label>
                    {{ html()->select('controller', [$ticket->controller_id => $ticket->Controller_name], null)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="position" class="form-label">Session Category</label>
                    {{ html()->select('position', $positions, $ticket->position)->placeholder('Select Position')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="session_id" class="form-label">Session ID</label>
                    {{ html()->select('session_id', $session_ids, $ticket->session_id)->placeholder('Select Session ID')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="type" class="form-label">Session Type</label>
                    {{ html()->select('type', $progress_types, $ticket->type)->placeholder('Select Session Type')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', $ticket->date)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="start" class="form-label">Start Time in Eastern</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start', $ticket->start_time)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="end" class="form-label">End Time in Eastern</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end', $ticket->end_time)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="duration" class="form-label">Duration (hh:mm)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-target-input="nearest">
                        {{ html()->text('duration', $ticket->duration)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker4']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="score" class="form-label">Score (Visible to Controller)</label>
                    <div class="input-group">
                        {{ html()->text('score', $ticket->score)->placeholder('1-5')->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="movements" class="form-label">Movements (Optional)</label>
                    <div class="input-group">
                        {{ html()->text('movements', $ticket->movements)->class('form-control') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="comments" class="form-label">Comments (Visible to Controller and other Trainers)</label>
                    {{ html()->textarea('comments', $ticket->comments)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="trainer_comments" class="form-label">Trainer Comments (Visible to Only Other Trainers)</label>
                    {{ html()->textarea('trainer_comments', $ticket->ins_comments)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <label for="monitor" class="form-label">Can be monitored</label>
        @if($ticket->monitor == 1)
			{{ html()->checkbox('monitor', true, 1) }}
        @else
			{{ html()->checkbox('monitor', false, 1) }}
        @endif	
        <br>
        <label for="cert" class="form-label">Certification or Solo Issued</label>
        @if($ticket->cert == 1)
			{{ html()->checkbox('cert', true, 1) }}
        @else
			{{ html()->checkbox('cert', false, 1) }}
        @endif		
		<br>
        <br>
        <button class="btn btn-success" action="submit">Save Ticket</button>
        <a href="/dashboard/training/tickets/view/{{ $ticket->id }}" class="btn btn-danger">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{asset('js/trainingticket.js')}}"></script>
@endsection
