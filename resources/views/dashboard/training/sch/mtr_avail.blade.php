@extends('layouts.dashboard')

@section('title')
@parent
| Training
@stop

@section('content')

<div class="page-heading-two">
	<div class="container">
		<h2>Training - Request Session</h2>
	</div>
</div>

@if(Auth::user()->canTrain == 0)
<div class="container">
<div class="row">
	<div class="col-sm-12">
		<center><p>Your Training Has Been Disabled By The Instructors</p></center>
	</div>
</div>
@else
<div class="container">
	<legend>
		Select an available time slot for training. Current Time: <span class="time"></span> EST
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

	{{ Form::open(['action' => 'TrainingController@saveSession', 'class' => 'session-request-form']) }}
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					{{Form::label('position', 'Position:', ['class'=>'control-label'])}}
					{{Form::select('position', App\TrainingRequest::$PosReq, 1, ['class'=>'form-control'])}}
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					{{Form::label('date', 'Slot:', ['class'=>'control-label'])}}
					{{Form::text('date', null, ['class'=>'form-control', 'disabled' => 'disabled'])}}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{{Form::label('comments', 'Comments:', ['class'=>'control-label'])}}
					{{Form::textarea('comments', null, ['class'=>'form-control'])}}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
				</div>
			</div>
		</div>
	{{ Form::close() }}
</div>



@endif

@stop
