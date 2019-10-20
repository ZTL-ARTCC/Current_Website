@extends('layouts.dashboard')

@section('title')
    Training
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Training - Request Session</h2>
    &nbsp;
</div>
<br>
<div class="container">
    <legend>
        Selcet an available time slot for training. Current time: <span class="time"></span> EST
    </legend>

    <div class="table-responsive">
		<table class="availability table table-bordered table-condensed">
			<thead>
				<tr></tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>