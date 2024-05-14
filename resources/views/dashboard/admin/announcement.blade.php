@extends('layouts.dashboard')

@section('title')
Announcement
@endsection

@section('content')
@include('inc.header', ['title' => 'Announcement'])

<div class="container">
    {!! Form::open(['action' => 'AdminDash@saveAnnouncement']) !!}
        @csrf
        <div class="form-group">
            {!! Form::label('body', 'Announcement (Leave Blank to Remove the Announcement):', ['class' => 'control-label']) !!}
            {!! Form::textArea('body', $announcement->body, ['placeholder' => 'Leave Blank for no Announcement', 'class' => 'form-control text-editor']) !!}
        </div>
        <p class="small"><i>Last edited by {{ $announcement->staff_name }} on {{ $announcement->update_time }}</i></p>
        <button class="btn btn-success" type="submit">Save Announcement</button>
    {!! Form::close() !!}
</div>

@endsection
