@extends('layouts.dashboard')

@section('title')
Training Team Feedback Details
@endsection

@section('content')
@include('inc.header', ['title' => 'Showing Training Team Feedback Details for ' . $feedback->controller_name])

<div class="container">
    <a href="/dashboard/controllers/profile" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
    <br><br>

    <div class="card">
        <div class="card-header">
            <h3>
                Feedback Left on {{ $feedback->created_at }}, Saved at {{ $feedback->updated_at }}
            </h3>
        </div>
        <div class="card-body">
            <p><b>Lesson ID or Position Trained:</b> {{ $feedback->position_trained }}</p>
            <p><b>Service Level:</b> {{ $feedback->service_level_text }}</p>
            <p><b>Training Method:</b> {{ $feedback->training_method }}</p>
            <p><b>Student Comments:</b></p>
            @if($feedback->comments != null)
                <p>{!! nl2br(e($feedback->comments)) !!}</p>
            @else
                <p>The student did not leave any comments.</p>
            @endif
            <p><b>Staff Comments:</b></p>
            @if($feedback->staff_comments != null)
                <p>{!! nl2br(e($feedback->staff_comments)) !!}</p>
            @else
                <p>The training staff have not left any comments.</p>
            @endif
        </div>
    </div>
</div>
@endsection
