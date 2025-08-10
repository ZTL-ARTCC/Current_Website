@extends('layouts.dashboard')

@section('title')
Live Event Information
@endsection

@section('content')
@include('inc.header', ['title' => 'Live Event Information'])

<div class="container">
    {{ html()->form()->route('saveLiveEventInfo')->open() }}
        @csrf
        <div class="form-group">
            <label for="event_title" class="control-label">Live Event Name</label>
            {{ html()->text('event_title', $liveInfo->event_title)->placeholder('Leave blank for no info')->class(['form-control']) }}
            <br>
            <label for="body_public" class="control-label">Set Live Event Public Info (visible to everyone)</label>
            {{ html()->textarea('body_public', $liveInfo->body_public)->placeholder('Leave blank for no info')->class(['form-control text-editor']) }}
            <br>
            <label for="body_private" class="control-label">Set Live Event Private Info (added to information above when accessed from within the dashboard)</label>
            {{ html()->textarea('body_private', $liveInfo->body_private)->placeholder('Leave blank for no info')->class(['form-control text-editor']) }}
        </div>
        <p class="small"><i>Last edited by {{ $liveInfo->staff_name }} on {{ $liveInfo->update_time }}</i></p>
        Publish this view?
        &nbsp;
        <?php
            $pub = ($liveInfo->publish) ? 'checked' : '';
        ?>
        <label class="switch mb-3">
            <input type="checkbox" name="publish" value="1" {{ $pub }}>
            <span class="slider round"></span>
        </label>
        <br>
        <button class="btn btn-success" type="submit">Save Live Event Info</button>
    {{ html()->form()->close() }}
</div>

@endsection
