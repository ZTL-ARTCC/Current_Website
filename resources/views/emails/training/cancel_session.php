<h2>Attention!!</h2>

<h3>The following session has been cancelled!</h3>

<h2>Session information:</h2>

<ul>
	<li>Time Slot: <b>{{ $session->slot }}</b></li>
	<li>Mentor/Instructor: <b>{{ $session->mentor->full_name }}</b></li>
	<li>Student: <b>{{ $session->trainee->full_name }}</b></li>
	<li>Position: <b>{{ $session->pos_req }}</b></li>
	<li>Comments: <b>{{ $session->trainee_comments }}</b></li>
	<li>Message: <b>{{ $session->cancel_message }}</b></li>
</ul>

