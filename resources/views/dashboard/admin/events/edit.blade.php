@extends('layouts.dashboard')

@section('title')
New Event
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>New Event</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => ['AdminDash@saveEvent', $event->id], 'files' => 'true']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {!! Form::label('name', 'Event Name') !!}
                    {!! Form::text('name', $event->name, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('host', 'Event Host') !!}
                    {!! Form::text('host', $event->host, ['class' => 'form-control', 'placeholder' => 'Event Host']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('date', 'Date of Event') !!}
                    {!! Form::date('date', $event->date_edit, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('start_time', 'Start Time (Zulu)') !!}
                    {!! Form::time('start_time', $event->start_time, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('end_time', 'End Time (Zulu)') !!}
                    {!! Form::time('end_time', $event->end_time, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Event Description') !!}
            {!! Form::textArea('description', $event->description, ['id' => 'article-ckeditor', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('banner', 'Upload Banner') !!}
            {!! Form::file('banner', ['class' => 'form-control']) !!}
        </div>
        <button class="btn btn-success" type="submit">Save Event</button>
        <a class="btn btn-danger" href="/dashboard/controllers/events">Cancel</a>
    {!! Form::close() !!}
</div>

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'article-ckeditor' );
</script>
@endsection
