@extends('layouts.dashboard')

@section('title')
@parent
| Instructing
@stop

@section('content')

<div class="page-heading-two">
	<div class="container">
		<h2>Admin - Instructing - Training Sessions</h2>
	</div>
</div>

<div class="container">
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="15%">Slot</th>
					<th width="15%">Position</th>
					<th width="15%">Student</th>
					<th width="15%">Mentor</th>
					<th width="35%">Comments</th>
					<th width="5%">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse($sessions as $session)
				<tr>
					<td>{{{$session->slot}}}</td>
					<td>{{{$session->pos_req}}}</td>
					<td>{{{$session->Trainee->full_name}}}</td>
					<td>{{{$session->mentor->full_name}}}</td>
					<td>{{{$session->trainee_comments}}}</td>
					<td>@if($session->mentor_id == Auth::id())
						<a href="#" data-toggle="modal" data-target="#cancelModal-{{$session->id}}" class="btn btn-danger btn-sm simple-tooltip" title="Cancel Session"><i class="fa fa-times"></i></a>
                                
	                    <div class="modal fade" id="cancelModal-{{$session->id}}">
	                        <div class="modal-dialog">
	                                <div class="modal-content">
	                                {{ Form::open(['action'=>['MentorController@cancelSession', $session->id]])}}

	                                    <div class="modal-header">
	                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                        <h4 class="modal-title">Cancel Session</h4>
	                                    </div>
	                                    <div class="modal-body">
	                                        <div class="form-group">
	                                            {{Form::label('cancel', 'Cancel Message:', ['class'=>'control-label'])}}
	        									{{Form::textarea('cancel', null, ['class'=>'form-control'])}}
	                                        </div>
	                                    </div>
	                                    <div class="modal-footer">
	                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                                        <button type="submit" class="btn btn-primary">Submit</a>
	                                    </div>

	                                {{ Form::close() }}
	                            </div>
	                        </div>
	                    </div>
						@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="6"><center>No Student Sessions</center></td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>

@stop