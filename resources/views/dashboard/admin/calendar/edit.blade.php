@extends('layouts.dashboard')

@section('title')
Edit Calendar Event/News
@endsection

@section('content')
<div class="container-fluid view-header">
    <h2>Edit Calendar Event/News</h2>
</div>
<br>
<div class="container">
    {!! Form::open(['action' => ['AdminDash@saveCalendarEvent', $calendar->id]]) !!}
    @csrf
    <div class="form-group">
    {!! Form::label('title', 'Title') !!}
    {!! Form::text('title', $calendar->title, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
    </div>
    <div class="form-group">
        <div class="row">
        <div class="col-sm-6">
                    {!! Form::label('date', 'Date') !!}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', $calendar->date, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('time', 'Time') !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('time', $calendar->time, ['placeholder' => 'HH:MM (Optional)', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('body', 'Additional Information') !!}
        {!! Form::textArea('body', $calendar->body, ['class' => 'form-control text-editor', 'placeholder' => 'Required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('type', 'Type of Post') !!}
        {!! Form::select('type', [
            1 => 'Calendar Event',
            2 => 'News'
        ], $calendar->type, ['class' => 'form-control']) !!}
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
