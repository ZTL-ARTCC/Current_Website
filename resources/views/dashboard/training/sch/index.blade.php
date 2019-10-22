@extends('layouts.dashboard')

@section('title')
@parent
| Training
@stop

@section('content')

<div class="page-heading-two">
	<div class="container">
		<h2>Training - Upcoming Sessions</h2>
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
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="15%">Slot</th>
					<th width="15%">Position</th>
					<th width="20%">Mentor/Instructor</th>
					<th width="45%">Comments</th>
					<th width="5%">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse($sessions as $session)
				<tr>
					<td>{{ $session->slot }}</td>
					<td>{{{$session->pos_req}}}</td>
					<td>{{ $session->mentor->full_name }}</td>
					<td>{{{ $session->trainee_comments }}}</td>
					<td>
						{{Form::open(['action'=>['TrainingController@cancelSession', $session->id], 'method'=>'delete', 'style'=>'display:inline-block'])}}
                            <button type="submit" class="btn btn-danger btn-sm simple-tooltip" title="Delete Request"><i class="fa fa-times"></i></button>
                        {{Form::close()}}
                    </td>
				</tr>
				@empty
	            <tr><td colspan="5">No Upcoming Sessions</td></tr>
	            @endforelse
			</tbody>
		</table>
	</div>
</div>
@endif

@stop
