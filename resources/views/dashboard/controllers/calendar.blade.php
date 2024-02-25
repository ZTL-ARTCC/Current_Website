@extends('layouts.dashboard')

@section('title')
View Calendar Event/News
@endsection

@section('content')
@if($calendar->type == 1)
    @include('inc.header', ['title' => 'Viewing Calendar Event, "' . $calendar->title . '"'])
@else
    @include('inc.header', ['title' => 'Viewing News Posting, "' . $calendar->title . '"'])
@endif

<div class="container">
    <a href="/dashboard" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
    <br><br>

    <div class="card">
        <div class="card-header">
            <h3>
                {{ $calendar->title }}
            </h3>
        </div>
        <div class="card-body">
            <p><b>Date:</b> {{ $calendar->date }}</p>
            <p><b>Time:</b> @if($calendar->time != null) {{ $calendar->time }} @else No time listed. @endif</p>
            <p><b>Additional Information:</b></p>
            <p>{!! $calendar->body !!}</p>
            <hr>
            <p class="small"><i>Created by {{ App\User::find($calendar->created_by)->full_name }} at {{ $calendar->created_at }}</i></p>
            @if($calendar->updated_by != null)
                <p class="small"><i>Last updated by {{ App\User::find($calendar->updated_by)->full_name }} at {{ $calendar->updated_at }}</i></p>
            @endif
        </div>
    </div>
</div>
@endsection
