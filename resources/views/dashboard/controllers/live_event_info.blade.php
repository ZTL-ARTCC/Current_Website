@extends('layouts.dashboard')

@section('title')
Live Event Information
@endsection

@section('content')
@include('inc.header', ['title' => 'Live Event Information'])

<div class="container">
    @toggle('live_event')
        <h3>{{ $liveEventInfo->event_title }}</h3>
        {{ $liveEventInfo->body_public }}

        {{ $liveEventInfo->body_private }}
    @else
        <p>We're sorry, but there is no event information available at this time. Please check back later.</p>
    @endtoggle
</div>

@endsection
