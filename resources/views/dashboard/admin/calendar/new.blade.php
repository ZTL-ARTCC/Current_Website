@extends('layouts.dashboard')

@section('title')
New Calendar Event/News
@endsection

@section('content')
@include('inc.header', ['title' => 'New Calendar Event/News'])

<div class="container">
        {!! Form::open(['action' => 'AdminDash@storeCalendarEvent']) !!}
        @csrf
        <div class="form-group">
        {!! Form::label('title', 'Title') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('date', 'Date') !!}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('time', 'Time') !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('time', null, ['placeholder' => 'HH:MM (Optional)', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('body', 'Additional Information') !!}
            {!! Form::textArea('body', null, ['class' => 'form-control text-editor', 'placeholder' => 'Required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('type', 'Type of Post') !!}
            {!! Form::select('type', [
                1 => 'Calendar Event',
                2 => 'News'
            ], null, ['class' => 'form-control']) !!}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
    {!! Form::close() !!}
            <div class="col-sm-1">
                <a href="/dashboard/admin/calendar" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>

@endsection
