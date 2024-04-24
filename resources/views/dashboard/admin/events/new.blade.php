@extends('layouts.dashboard')

@section('title')
New Event
@endsection

@section('content')
@include('inc.header', ['title' => 'New Event'])

<div class="container">
    {{ html()->form()->route('AdminDash@saveNewEvent')->acceptsFiles() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {{ html()->label('Event Name', 'name') }}
                    {{ html()->text('name', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    {{ html()->label('Event Host', 'host') }}
                    {{ html()->text('host', null)->class(['form-control'])->placeholder('Event Host') }}
                </div>
                <div class="col-sm-4">
                    {{ html()->label('Date of Event', 'date')->class(['form-label']) }}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
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
                    {{ html()->label('Start Time (Zulu)', 'start_time')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start_time', null)->placeholder('00:00')->class(['form-control'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    {{ html()->label('End Time (Zulu)', 'end_time')->class(['form-label']) }}
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end_time', null)->placeholder('00:00')->class(['form-control'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    {{ html()->label('Event Type', 'type')->class(['form-label']) }}
                    {{ html()->select('type', ['Local Event', 'Support Event', 'Support Event (unverified)'], 'Local Event')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ html()->label('Event Description', 'description') }}
            {{ html()->textarea('description', null)->class(['form-control', 'text-editor']) }}
        </div>
        <div class="form-group">
            {{ html()->label('Upload Banner or Enter Banner URL', 'banner') }} 
            {{ html()->file('banner')->class(['form-control']) }} 
            <span>OR</span>
            {{ html()->text('banner_url', null)->class(['form-control'])->placeholder('Enter URL for the banner image') }}
        </div>
        <button class="btn btn-success" type="submit">Save Event</button>
        <a class="btn btn-danger" href="/dashboard/controllers/events">Cancel</a>
    {{ html()->form()->close() }}
</div>
@endsection
