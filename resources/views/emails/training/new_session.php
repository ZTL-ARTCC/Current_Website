@extends('layouts.email')

@section('content')
<h2>Attention!!</h2>

<p>A new session has been created for the following time slot: <b>{{ $Slot->slot }}</b></p>

<h2>Session information:</h2>

<ul>
	<li>Mentor/Instructor: <b>{{ $Slot->mentor->fname }}</b></li>
	<li>Student: <b>{{ $Slot->trainee->fname }}</b></li>
	<li>Position: <b>{{ $Slot->pos_req }}</b></li>
	<li>Comments: <b>{{ $Slot->trainee_comments }}</b></li>
</ul>

<p>If you are unable to make the session you can cancel via the session management interface.</p>
@endsection