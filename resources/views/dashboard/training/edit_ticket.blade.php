@extends('layouts.dashboard')

@section('title')
Edit Training Ticket
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Training Ticket'])

<div class="container">
    {!! Form::open(['action' => ['TrainingDash@saveTicket', $ticket->id]]) !!}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller', ['class' => 'form-label']) !!}
                    {!! Form::select('controller', [$ticket->controller_id => $ticket->Controller_name], null ,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('position', 'Session Category', ['class' => 'form-label']) !!}
                    {!! Form::select('position', $positions, $ticket->position, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('session_id', 'Session ID', ['class' => 'form-label']) !!}
                    {!! Form::select('session_id', $session_ids, $ticket->session_id, ['placeholder' => 'Select Session ID', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('type', 'Session Type', ['class' => 'form-label']) !!}
                    {!! Form::select('type', $progress_types, $ticket->type, ['placeholder' => 'Select Session Type', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('date', 'Date', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', $ticket->date, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('start', 'Start Time in Eastern', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('start', $ticket->start_time, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('end', 'End Time in Eastern', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {!! Form::text('end', $ticket->end_time, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker3']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('duration', 'Duration (hh:mm)', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-target-input="nearest">
                        {!! Form::text('duration', $ticket->duration, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker4']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('comments', 'Comments (Visible to Controller and other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('comments', $ticket->comments, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('trainer_comments', 'Trainer Comments (Visible to Only Other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('trainer_comments', $ticket->ins_comments, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
		{!! Form::label('monitor', 'Can be monitored', ['class' => 'form-label']) !!}
        @if($ticket->monitor == 1)
			{!! Form::checkBox('monitor', 1, true) !!}
        @else
			{!! Form::checkBox('monitor', 1) !!}
        @endif	
        <br>
		{!! Form::label('cert', 'Certification or Solo Issued', ['class' => 'form-label']) !!}
        @if($ticket->cert == 1)
			{!! Form::checkBox('cert', 1, true) !!}
        @else
			{!! Form::checkBox('cert', 1) !!}
        @endif		
		<br>
        <br>
        <button class="btn btn-success" action="submit">Save Ticket</button>
        <a href="/dashboard/training/tickets/view/{{ $ticket->id }}" class="btn btn-danger">Cancel</a>
    {!! Form::close() !!}
</div>
{{Html::script(asset('js/trainingticket.js'))}}
@endsection
