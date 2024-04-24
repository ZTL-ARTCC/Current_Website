@extends('layouts.dashboard')

@section('title')
Edit Training Ticket
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Training Ticket'])

<div class="container">
    {{ html()->form()->route('TrainingDash@saveTicket', [$ticket->id]) }}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Controller', 'controller')->class(['form-label']) }}
                    {{ html()->select('controller', [$ticket->controller_id => $ticket->Controller_name], null)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Session Category', 'position')->class(['form-label']) }}
                    {{ html()->select('position', $positions, $ticket->position)->placeholder('Select Position')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Session ID', 'session_id')->class(['form-label']) }}
                    {{ html()->select('session_id', $session_ids, $ticket->session_id)->placeholder('Select Session ID')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Session Type', 'type')->class(['form-label']) }}
                    {{ html()->select('type', $progress_types, $ticket->type)->placeholder(Select Session Type')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Date', 'date')->class(['form-label']) }}
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
                    {{ html()->label('Start Time in Eastern', 'start')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start', $ticket->start_time)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('End Time in Eastern', 'end')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end', $ticket->end_time)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Duration (hh:mm)', 'duration')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-target-input="nearest">
                        {{ html()->text('duration', $ticket->duration)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker4']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {{ html()->label('Comments (Visible to Controller and other Trainers)', 'comments')->class(['form-label']) }}
                    {{ html()->textarea('comments', $ticket->comments)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {{ html()->label('Trainer Comments (Visible to Only Other Trainers)', 'trainer_comments')->class(['form-label']) }}
                    {{ html()->textarea('trainer_comments', $ticket->ins_comments)->class(['form-control']) }}
                </div>
            </div>
        </div>
		{{ html()->label('Can be monitored', 'monitor')->class(['form-label']) }}
        @if($ticket->monitor == 1)
			{{ html()->checkbox('monitor', true, 1) }}
        @else
			{{ html()->checkbox('monitor', false, 1) }}
        @endif	
        <br>
		{{ html()->label('Certification or Solo Issued', 'cert')->class(['form-label']) }}
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
<script src="{{asset('js/trainingticket.js}}')">
@endsection
