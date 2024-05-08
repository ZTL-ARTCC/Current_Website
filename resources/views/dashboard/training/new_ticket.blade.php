@extends('layouts.dashboard')

@section('title')
New Training Ticket
@endsection

@section('content')
@include('inc.header', ['title' => 'Submit New Training Ticket'])

<div class="container">
    {{ html()->form()->route('saveNewTicket')->open() }}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="controller" class="form-label">Controller</label>
                    @php
                        $c = (is_null($c)) ? null : $c;
                    @endphp
                    {{ html()->select('controller', $controllers, $c)->placeholder('Select Controller')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="position" class="form-label">Session Category</label>
                    {{ html()->select('position', $positions, null)->placeholder('Select Training Session')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="session_id" class="form-label">Session ID</label>
                    {{ html()->select('session_id', $session_ids, null)->placeholder('Select Session ID')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="type" class="form-label">Progress</label>
                    {{ html()->select('type', $progress_types, null)->placeholder('Select Progress Type')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
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
                    <label for="start" class="form-label">Start Time ET (now {{ $currentTimeET }})</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start', null)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="end" class="form-label">End Time ET</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end', null)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="duration" class="form-label">Duration (hh:mm)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-target-input="nearest">
                        {{ html()->text('duration', null)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker4']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="comments" class="form-label">Comments (Visible to Controller and other Trainers)</label>
                    {{ html()->textarea('comments', null)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="trainer_comments" class="form-label">Trainer Comments (Visible to Only Other Trainers)</label>
                    {{ html()->textarea('trainer_comments', null)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <label for="ots" class="form-label">Recommend for OTS?</label>
        {{ html()->checkbox('ots', false, '1') }}
        <br>
        <label for="monitor" class="form-label">Can be monitored</label>
		{{ html()->checkbox('monitor', false, '1') }}
		<br>
        <label for="cert" class="form-label">Certification or Solo Issued</label>
		{{ html()->checkbox('cert', false, 1) }}
		<br>
        <button class="btn btn-success" action="submit">Submit Ticket</button>
        <a href="/dashboard/training/tickets" class="btn btn-danger">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{asset('js/trainingticket.js')}}">
@endsection
