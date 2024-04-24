@extends('layouts.dashboard')

@section('title')
New Training Ticket
@endsection

@section('content')
@include('inc.header', ['title' => 'Submit New Training Ticket'])

<div class="container">
    {{ html()->form()->route('TrainingDash@saveNewTicket') }}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Controller', 'controller')->class(['form-label']) }}
                    @php
                        $c = (is_null($c)) ? null : $c;
                    @endphp
                    {{ html()->select('controller', $controllers, $c)->placeholder('Select Controller')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Session Category', 'position')->class(['form-label']) }}
                    {{ html()->select('position', $positions, null)->placeholder('Select Training Session')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Session ID', 'session_id')->class(['form-label']) }}
                    {{ html()->select('session_id', $session_ids, null)->placeholder('Select Session ID')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Progress', 'type')->class(['form-label']) }}
                    {{ html()->select('type', $progress_types, null)->placeholder('Select Progress Type')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Date', 'date')->class(['form-label']) }}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
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
                    {{ html()->label('Start Time ET (now ' . $currentTimeET . ')', 'start')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start', null)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('End Time ET', 'end')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end', null)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{ html()->label('Duration (hh:mm)', 'duration')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-target-input="nearest">
                        {{ html()->text('duration', null)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker4']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {{ html()->label('Comments (Visible to Controller and other Trainers)', 'comments')->class(['form-label']) }}
                    {{ html()->textarea('comments', null)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {{ html()->label('Trainer Comments (Visible to Only Other Trainers)', 'trainer_comments')->class(['form-label']) }}
                    {{ html()->textarea('trainer_comments', null)->class(['form-control']) }}
                </div>
            </div>
        </div>
        {{ html()->label('Recommend for OTS?', 'ots')->class(['form-label']) }}
        {{ html()->checkbox('ots', false, '1') }}
        <br>
		{{ html()->label('Can be monitored', 'monitor')->class(['form-label']) }}
		{{ html()->checkbox('monitor', false, '1') }}
		<br>
	    {{ html()->label('Certification or Solo Issued', 'cert')->class(['form-label']) }}
		{{ html()->checkbox('cert', false, 1) }}
		<br>
        <button class="btn btn-success" action="submit">Submit Ticket</button>
        <a href="/dashboard/training/tickets" class="btn btn-danger">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{asset('js/trainingticket.js')}}">
@endsection
