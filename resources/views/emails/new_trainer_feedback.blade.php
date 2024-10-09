@extends('layouts.email')

@section('content')
<p>Dear {{ $controller->full_name }},</p>

<p>You have new training team feedback that has been approved. You can see the details below or from the <a href="https://www.ztlartcc.org/dashboard/controllers/profile">"My Profile"</a> page on the website.</p>
<br>

<p><b>Submitted at:</b> {{ $feedback->created_at }}</p>
<p><b>Lesson ID or Position Trained:</b> {{ $feedback->position_trained }}</p>
<p><b>Service Level:</b> {{ $feedback->service_level_text }}</p>
<p><b>Training Method:</b> {{ $feedback->training_method_text }}</p>
<p><b>Comments:</b></p>
<p>{!! nl2br(e($feedback->comments)) !!}</p>
<p><b>Staff Comments:</b></p> 
@if($feedback->staff_comments != null)
    <p>{!! nl2br(e($feedback->staff_comments)) !!}</p>
@else
    <p>No staff comments.</p>
@endif
<br>

<p>If you have any thoughts/comments, please email the TA at <a href="mailto:ta@ztlartcc.org">ta@ztlartcc.org</a>.</p>
<br>

<p>Sincerely,</p>
<p>ZTL ARTCC Training Team</p>
@endsection
