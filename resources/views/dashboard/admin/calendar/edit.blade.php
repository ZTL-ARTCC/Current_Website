@extends('layouts.dashboard')

@section('title')
Edit Calendar Event/News
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Edit Calendar Event/News</h2>
    &nbsp;
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
                {!! Form::text('date', $calendar->date, ['class' => 'form-control', 'placeholder' => 'MM/DD/YYYY (Required)']) !!}
            </div>
            <div class="col-sm-6">
                {!! Form::label('time', 'Time') !!}
                {!! Form::text('time', $calendar->time, ['class' => 'form-control', 'placeholder' => 'HH:MM (Optional)']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('body', 'Additional Information') !!}
        {!! Form::textArea('body', $calendar->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Required']) !!}
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

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'article-ckeditor' );
</script>
@endsection
