<h2>Attention!!</h2>

<p>A new session has been created for the following time slot: <b>{{ $session->slot }}</b></p>

<h2>Session information:</h2>

<ul>
	<li>Mentor/Instructor: <b>{{ $session->mentor->full_name }}</b></li>
	<li>Student: <b>{{ $session->trainee->full_name }}</b></li>
	<li>Position: <b>{{ $session->pos_req }}</b></li>
	<li>Comments: <b>{{ $session->trainee_comments }}</b></li>
</ul>

<p>If you are unable to make the session you can cancel via the session management interface.</p>
