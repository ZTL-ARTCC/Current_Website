@extends('layouts.master')

@section('title')
Live Event Information
@endsection

@section('content')
@include('inc.header', ['title' => 'Live Event Information', 'type' => 'external'])

<div class="container">
    @if($liveEventInfo->publish)
        <h3>{{ $liveEventInfo->event_title }}</h3>
        {!! $liveEventInfo->body_public !!}
    @else
        <p>We're sorry, but there is no event information available at this time. Please check back later.</p>
    @endif
</div>

@endsection
