@extends('layouts.dashboard')

@section('title')
New Event
@endsection

@section('content')
@include('inc.header', ['title' => 'New Event'])

<div class="container">
    {!! Form::open(['action' => 'AdminDash@saveNewEvent', 'files' => 'true']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {!! Form::label('name', 'Event Name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('host', 'Event Host') !!}
                    {!! Form::text('host', null, ['class' => 'form-control', 'placeholder' => 'Event Host']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('date', 'Date of Event', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {!! Form::label('start_time', 'Start Time (Zulu)', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('start_time', null, ['placeholder' => '00:00', 'class' => 'form-control', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('end_time', 'End Time (Zulu)', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {!! Form::text('end_time', null, ['placeholder' => '00:00', 'class' => 'form-control', 'data-target' => '#datetimepicker3']) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('type', 'Event Type', ['class' => 'form-label']) !!}
                    {!! Form::select('type', ['Local Event', 'Support Event', 'Support Event (unverified)'], 'Local Event', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Event Description') !!}
            {!! Form::textArea('description', null, ['class' => 'form-control text-editor']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('banner', 'Upload Banner or Enter Banner URL (Optional)') !!} 
            {!! Form::file('banner', ['class' => 'form-control']) !!} 
            <p>OR</p>
            {!! Form::text('banner_url', null, ['class' => 'form-control', 'placeholder' => 'Enter URL for the banner image']) !!}
        </div>
        <button class="btn btn-success" type="submit">Save Event</button>
        <a class="btn btn-danger" href="/dashboard/controllers/events">Cancel</a>
    {!! Form::close() !!}
</div>
@endsection
