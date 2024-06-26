@extends('layouts.dashboard')

@section('title')
Announcement
@endsection

@section('content')
@include('inc.header', ['title' => 'Announcement'])

<div class="container">
    {{ html()->form()->route('saveAnnouncement')->open() }}
        @csrf
        <div class="form-group">
            <label for="body" class="control-label">Announcement (Leave Blank to Remove the Announcement)</label>
            {{ html()->textarea('body', $announcement->body)->placeholder('Leave Blank for no Announcement')->class(['form-control text-editor']) }}
        </div>
        <p class="small"><i>Last edited by {{ $announcement->staff_name }} on {{ $announcement->update_time }}</i></p>
        <button class="btn btn-success" type="submit">Save Announcement</button>
    {{ html()->form()->close() }}
</div>

@endsection
