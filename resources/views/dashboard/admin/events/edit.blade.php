@extends('layouts.dashboard')

@section('title')
New Event
@endsection

@section('content')
@include('inc.header', ['title' => 'New Event'])

<div class="container">
    {{ html()->form()->route('saveEvent', [$event->id])->acceptsFiles() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label for="name">Event Name</label>
                    {{ html()->text('name', $event->name)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    <label for="host">Event Host</label>
                    {{ html()->text('host', $event->host)->class(['form-control'])->placeholder('Event Host') }}
                </div>
                <div class="col-sm-4">
                    <label for="date" class="form-label">Date of Event</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', $event->date)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
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
                    <label for="start_time" class="form-label">Start Time (Zulu)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start_time', $event->start_time)->placeholder('00:00')->class(['form-control'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <label for="end_time" class="form-label">End Time (Zulu)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end_time', $event->end_time)->placeholder('00:00')->class(['form-control'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <label for="type" class="form-label">Event Type</label>
                    {{ html()->select('type', ['Local Event', 'Support Event', 'Support Event (unverified)'], $event->type)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Event Description</label>
            {{ html()->textarea('description', $event->description)->class(['form-control', 'text-editor']) }}
        </div>
        <div class="form-group">
            <label for="banner">Upload Banner or Enter Banner URL</label>
            {{ html()->file('banner')->class(['form-control']) }} 
            <span>OR</span>
            {{ html()->text('banner_url', null)->class(['form-control'])->placeholder('Enter URL for the banner image') }}
        </div>
        <button class="btn btn-success" type="submit">Save Event</button>
        <a class="btn btn-danger" href="/dashboard/controllers/events">Cancel</a>
    {{ html()->form()->close() }}
</div>
@endsection
