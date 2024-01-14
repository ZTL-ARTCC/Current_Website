@extends('layouts.dashboard')

@section('title')
New Training Ticket
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Submit New Training Ticket</h2>
    &nbsp;
</div>
<br>

<div class="container">
    {!! Form::open(['action' => 'TrainingDash@saveNewTicket']) !!}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller', ['class' => 'form-label']) !!}
                    @if (!is_null($c))
                        $c = null;
                    @endif
                    {!! Form::select('controller', $controllers, $c, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('position', 'Session Category', ['class' => 'form-label']) !!}
                    {!! Form::select('position', $positions, null, ['placeholder' => 'Select Training Session', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('session_id', 'Session ID', ['class' => 'form-label']) !!}
                    {!! Form::select('session_id', $session_ids, null, ['placeholder' => 'Select Session ID', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('type', 'Progress', ['class' => 'form-label']) !!}
                    {!! Form::select('type', $progress_types, null, ['placeholder' => 'Select Progress Type', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('date', 'Date', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
					@php 
						$currentDateET = new DateTime("now", new DateTimeZone('America/New_York') ); 
						$currentTimeET = $currentDateET->format('H:i');
					@endphp
                    {!! Form::label('start', 'Start Time ET (now ' . $currentTimeET . ')', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('start', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('end', 'End Time ET', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        {!! Form::text('end', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker3']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('duration', 'Duration (hh:mm)', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                        {!! Form::text('duration', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker4']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('comments', 'Comments (Visible to Controller and other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('comments', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('trainer_comments', 'Trainer Comments (Visible to Only Other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('trainer_comments', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        {!! Form::label('ots', 'Recommend for OTS?', ['class' => 'form-label']) !!}
        {!! Form::checkBox('ots', '1') !!}
        <br>
		{!! Form::label('monitor', 'Can be monitored', ['class' => 'form-label']) !!}
		{!! Form::checkBox('monitor', '1') !!}
		<br>
	    {!! Form::label('cert', 'Certification or Solo Issued', ['class' => 'form-label']) !!}
		{!! Form::checkBox('cert', 1) !!}
		<br>
        <button class="btn btn-success" action="submit">Submit Ticket</button>
        <a href="/dashboard/training/tickets" class="btn btn-danger">Cancel</a>
    {!! Form::close() !!}
</div>
{{Html::script(asset('js/trainingticket.js'))}}
@endsection
