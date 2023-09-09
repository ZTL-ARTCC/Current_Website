@extends('layouts.dashboard')

@section('title')
View Event
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h3>Event Information and Signup</h3>
    &nbsp;
</div>
<br>
<div class="container">
    @if($event->banner_path != null)
        <div class="jumbotron">
            <img src="{{ $event->banner_path }}" width="100%" alt="{{ $event->name }}">
        </div>
    @endif
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3>
                        {{ $event->name }}
                    </h3>
                </div>
                <div class="card-body">
                    @if($event->host != null)
                        <h5><b>Host ARTCC:</b> {{ $event->host }}</h5>
                    @endif
                    <h5><b>Date:</b> {{ $event->date }}</h5>
                    <h5><b>Time:</b>
                            {{ $event->start_time }} to {{ $event->end_time }} Zulu
                            ({{ $local_start_time }} to {{ $local_end_time }} local <a style="color:inherit" href="#"
                                                                                 data-toggle="tooltip"
                                                                                 title="Showing times in {{ $timezone }}. You can change this on your profile."><i
                                        class="fas fa-info-circle"></i></a>)
                        </h5>
                        <h5><b>Type:</b>
                            @if(Auth::user()->isAbleTo('events'))
                                @if($event->type == 0)
                                    Local Event
                                @elseif($event->type == 1)
                                    Support Event
                                @else
                                    Unverified Support Event
                                    <a style="color:inherit" href="#" data-toggle="tooltip" title="Verify by setting the Event Type to 'Support Event' on the Edit Event page."><i class="fas fa-info-circle"></i></a>
                                @endif
                            @else
                                @if($event->type == 0)
                                    Local Event
                                @else
                                    Support Event
                                @endif
                            @endif
                        </h5>
                    <p><b>Additional Information:</b></p>
                    <p>{!! $event->description !!}</p>
                </div>
            </div>
            @if(Auth::user()->isAbleTo('events')||Auth::user()->hasRole('events-team'))
                <br>
                <div class="card">
                    <div class="card-header">
                        <h3>
                            Position Requests
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->isAbleTo('events'))
                            <p>
                                <i>Assign Positions:</i>
                                <span class="float-right" data-toggle="modal" data-target="#manualAssign">
                                    <button type="button" class="btn btn-success btn-sm pull-right" data-placement="top">Manual Assign</button>
                                </span>
                            </p>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>

                                <th scope="col">Position</th>
                                <th scope="col">Controller</th>
                                <th scope="col">Availability</th>
                                @if(Auth::user()->isAbleTo('events'))
                                <th scope="col">Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @if($registrations->count() > 0)
                                    @foreach($registrations as $r)
                                        <tr>
                                            <td>{{ $r->position_name }}</td>
                                            <td>
                                                {{ $r->controller_name }}
                                            </td>
                                            <td>

                                                <a style="color:inherit" href="#" data-toggle="tooltip" title="
                                            @if($r->start_time != null)
                                                {{ timeToLocal($r->start_time, Auth::user()->timezone) }}
                                            @else
                                                {{ timeToLocal($event->start_time, Auth::user()->timezone) }}
                                            @endif
                                            -
                                            @if($r->end_time != null)
                                                {{ timeToLocal($r->end_time, Auth::user()->timezone) }}
                                            @else
                                                {{ timeToLocal($event->end_time, Auth::user()->timezone) }}
                                            @endif
                                             local">@if($r->start_time != null)
                                                        {{ $r->start_time }}
                                                    @else
                                                        {{ $event->start_time }}
                                                    @endif
                                                    -
                                                    @if($r->end_time != null)
                                                        {{ $r->end_time }}z
                                                    @else
                                                        {{ $event->end_time }}z
                                                    @endif</a>
                                            </td>
                                            @if(Auth::user()->isAbleTo('events'))
                                                <td>

                                                    <div class="btn-group" role="group" aria-label="Actions">
                                                        <button data-toggle="modal" data-target="#addrequest{{ $r->id }}" type="button" class="btn btn-success btn-sm simple-tooltip" data-placement="top" title="Assign Position">
                                                            <i class="fas fa-check fa-fw"></i>
                                                        </button>

                                                        <a href="/dashboard/controllers/events/view/{{ $r->id }}/un-signup" class="btn btn-danger btn-sm" title="Delete Request">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </a>

                                                        @if($r->remarks != null)
                                                            <button data-toggle="modal" data-target="#remarks{{ $r->id }}" type="button" class="btn btn-info btn-sm simple-tooltip" data-placement="top" title="View Remarks">
                                                                <i class="fas fa-info-circle fa-fw"></i>
                                                            </button>
                                                        @else
                                                            <button disabled data-toggle="modal" data-target="#remarks{{ $r->id }}" type="button" class="btn btn-info btn-sm disabled" data-placement="top" title="No Remarks Provided">
                                                                <i class="fas fa-info-circle fa-fw"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                            @endif
                                        </tr>

                                        <div class="modal fade" id="remarks{{ $r->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Position Remarks</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><i>Remarks submitted by {{ $r->controller_name }}:</i></p>
                                                        <p>{{ $r->remarks }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="addrequest{{ $r->id }}" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Assign Position</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    {!! Form::open(['action' => ['AdminDash@assignPosition', $r->id]]) !!}
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    {!! Form::label('controller_name', 'Controller Name') !!}
                                                                    {!! Form::text('controller_name', $r->controller_name, ['class' => 'form-control', 'disabled']) !!}
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    {!! Form::label('position', 'Position') !!}
                                                                    {!! Form::select('position', $positions->pluck('name', 'id'), $r->position_id, ['class' => 'form-control']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    {!! Form::label('position_detail', 'Position or sector ID assigned') !!}
                                                                    {!! Form::text('position_detail', null, ['class' => 'form-control']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    {!! Form::label('start_time', 'Start Time (Zulu)') !!}
                                                                    {!! Form::text('start_time', $r->start_time, ['placeholder' => $event->start_time, 'class' => 'form-control']) !!}
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    {!! Form::label('end_time', 'End Time (Zulu)') !!}
                                                                    {!! Form::text('end_time', $r->end_time, ['placeholder' => $event->end_time, 'class' => 'form-control']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                        <button action="submit" class="btn btn-success">Assign
                                                            Position
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No positions posted.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Signup/Position Assignments
                        @if(Auth::user()->isAbleTo('events'))
                            @if($event->reg == 0)
                                <a href="/dashboard/admin/events/toggle-reg/{{ $event->id }}" class="btn btn-success btn-simple-tooltip float-right" data-toggle="tooltip" title="Open Registration"><i class="fas fa-check"></i></a>
                            @else
                                <a href="/dashboard/admin/events/toggle-reg/{{ $event->id }}" class="btn btn-danger btn-simple-tooltip float-right" data-toggle="tooltip" title="Close Registration"><i class="fas fa-times"></i></a>
                            @endif
                            @toggle('event_assignment_toggle')
                                @if($event->show_assignments)
                                    <a href="/dashboard/admin/events/toggle-show-assignments/{{ $event->id }}" class="btn btn-danger btn-simple-tooltip float-right mr-2" data-toggle="tooltip" title="Hide Assignments"><i class="fas fa-eye-slash"></i></a>
                                @else
                                    <a href="/dashboard/admin/events/toggle-show-assignments/{{ $event->id }}" class="btn btn-success btn-simple-tooltip float-right mr-2" data-toggle="tooltip" title="Show Assignments"><i class="fas fa-eye"></i></a>
                                @endif
                            @endtoggle
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Position</th>
                                <th scope="col">Controller</th>
                                @if(Auth::user()->isAbleTo('events'))
                                    <th scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($positions->count() > 0)
                                @foreach($positions as $p)
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td>
                                            @if($p->controller->count() == 0 || (toggleEnabled('event_assignment_toggle') && ! (Auth::user()->isAbleTo('events') || Auth::user()->hasRole('events-team')) && ! $event->show_assignments))
                                                No Assignment
                                            @else
                                                @foreach($p->controller as $c)
                                                    <p>
                                                        @if(Auth::user()->isAbleTo('events'))
                                                            <a href="/dashboard/admin/events/positions/unassign/{{ $c->id }}"
                                                               style="color:inherit" data-toggle="tooltip"
                                                               title="Unassign Controller"><i class="fas fa-times"></i></a>
                                                            &nbsp;
                                                        @endif
                                                        <b>
                                                            {{ $c->controller_name }}
                                                            <i>
                                                                @if($c->start_time != null)
                                                                    ({{ $c->start_time }}
                                                                @else()
                                                                    ({{ $event->start_time }}
                                                                @endif
                                                                -
                                                                @if($c->end_time != null)
                                                                    {{ $c->end_time }}z)
                                                                    @else()
                                                                        {{ $event->end_time }}z)
                                                                    @endif

                                                                    <a style="color:inherit" href="#" data-toggle="tooltip" title="
                                            @if($c->start_time != null)
                                                {{ timeToLocal($c->start_time, Auth::user()->timezone) }}
                                            @else
                                                {{ timeToLocal($event->start_time, Auth::user()->timezone) }}
                                            @endif
                                            -
                                            @if($c->end_time != null)
                                                {{ timeToLocal($c->end_time, Auth::user()->timezone) }}
                                            @else
                                                {{ timeToLocal($event->end_time, Auth::user()->timezone) }}
                                            @endif
                                             your time (configurable in profile)"><i class="fas fa-clock"></i></a>

                                                                    @if($c->position_id_detail != null)
                                                                        &nbsp;
                                                                        {{ $c->position_id_detail }}
                                                                    @endif
                                                            </i>
                                                        </b>
                                                    </p>
                                                @endforeach
                                            @endif
                                        </td>
                                        @if(Auth::user()->isAbleTo('events'))
                                            <td>
                                                <a href="/dashboard/admin/events/position/delete/{{ $p->id }}"
                                                   class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip"
                                                   title="Remove Position"><i class="fas fa-times"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No positions posted.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <hr>
                    @if($positions->count() > 0)
                            {!! Form::open(['action' => 'ControllerDash@signupForEvent']) !!}
                            @csrf
                            @if($event->reg == 1 && Auth::user()->canEvents == 1)
                                {!! Form::hidden('event_id', $event->id) !!}
                                <div class="form-group">
                                    @if($your_registration1)
                                        {!! Form::hidden('yr1', $your_registration1->id) !!}
                                        <div class="row">
                                            @if($your_registration1->status == 0)
                                                <div class="col-sm-5">
                                                    {!! Form::select('num1', $positions->pluck('name', 'id'), $your_registration1->position_id, ['placeholder' => 'Position', 'class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-sm-3">
                                                    {!! Form::text('start_time1', $your_registration1->start_time, ['placeholder' => $event->start_time, 'class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-sm-3">
                                                    {!! Form::text('end_time1', $your_registration1->end_time, ['placeholder' => $event->end_time, 'class' => 'form-control']) !!}
                                                </div>
                                            @else
                                                <div class="col-sm-5">
                                                    @if(toggleEnabled('event_assignment_toggle') && ! $event->show_assignments)
                                                        {!! Form::select('num1', $positions->pluck('name', 'id'), null, ['disabled', 'placeholder' => 'Pending...', 'class' => 'form-control']) !!}
                                                    @else
                                                        {!! Form::select('num1', $positions->pluck('name', 'id'), $your_registration1->position_id, ['disabled', 'placeholder' => 'Choice 1', 'class' => 'form-control']) !!}
                                                    @endif
                                                </div>
                                                <div class="col-sm-3">
                                                    {!! Form::text('start_time1', $your_registration1->start_time, ['disabled', 'placeholder' => $event->start_time, 'class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-sm-3">
                                                    {!! Form::text('end_time1', $your_registration1->end_time, ['disabled', 'placeholder' => $event->end_time, 'class' => 'form-control']) !!}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        {!! Form::hidden('yr1', null) !!}
                                        <div class="row mb-2">
                                            <div class="col-sm-5 mt-1">
                                                {!! Form::select('num1', $positions->pluck('name', 'id'), null, ['autocomplete' => 'off', 'placeholder' => 'Desired Position', 'class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="form-inline">
                                                    {!! Form::hidden('timedata', $event->start_time.';'.$event->end_time.';'.timeToLocal($event->start_time, Auth::user()->timezone).';'.timeToLocal($event->end_time, Auth::user()->timezone), ['id' => 'timedata']) !!}
                                                    {!! Form::label('start_time1', 'Available from:', ['class' => 'form-label pr-2']) !!}
                                                    {!! Form::text('start_time1', null, ['autocomplete' => 'off', 'placeholder' => $event->start_time, 'class' => 'form-control col-sm-2 mr-2']) !!}
                                                    {!! Form::label('end_time1', '-', ['class' => 'form-label pr-2']) !!}
                                                    {!! Form::text('end_time1', null, ['autocomplete' => 'off', 'placeholder' => $event->end_time, 'class' => 'form-control col-sm-2 mr-2']) !!}
                                                    {!! Form::select('timezone', ['Zulu', 'Local'], 'Zulu', ['autocomplete' => 'off', 'class' => 'form-control col-sm-3', 'id' => 'timezone']) !!}

                                                </div>
                                            </div>
                                            <div class="col-sm-10 pt-2 pr-2">
                                                {!! Form::textarea('remarks', null, ['placeholder' => 'Specific position requests/additional information (optional)', 'class' => 'form-control textarea-no-resize col-sm-11 pr-2', 'rows' => '3', 'maxlength' => '1024']) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group inline">
                                    @if(!$your_registration1)
                                        <button type="submit" class="btn btn-success">Submit Request</button>
                                    @else
                                        <a href="/dashboard/controllers/events/view/{{ $your_registration1->id }}/un-signup"
                                           class="btn btn-danger">Delete your Signup</a>
                                    @endif
                                    <p class="pt-2"><i>Please note that the position assignments are made according to
                                            operational necessity and not all position preferences can be met.</i></p>
                                    <p class="pt-2"><i>Your local timezone on file is {{ Auth::user()->timezone }}. You
                                            can change this on <a href="/dashboard/controllers/profile">your
                                                profile.</a></i></p>
                                </div>
                            @else
                                @if(Auth::user()->canEvents != 1)
                                    You are not permitted to signup for events.
                                @else
                                    Event registration is currently closed.
                                @endif
                            @endif
                            {!! Form::close() !!}
                        @else
                            <p>No positions added.</p>
                        @endif

                        <hr title="Position Management">

                        @if(Auth::user()->isAbleTo('events'))
                            <br>
                            {!! Form::open(['action' => ['AdminDash@addPosition', $event->id]]) !!}
                            @csrf
                            <div class="row">
                                <div class="col-sm-10">
                                    {!! Form::text('name', null, ['placeholder' => 'New Position', 'class' => 'form-control']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-success simple-tooltip" data-toggle="tooltip"
                                            title="Add Position"><i class="fas fa-check"></i></button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <br>
                            <span data-toggle="modal" data-target="#savePreset">
                            <button type="button" class="btn btn-primary btn-sm" data-placement="top">Save as Position Preset</button>
                        </span>
                            <span data-toggle="modal" data-target="#loadPreset">
                            <button type="button" class="btn btn-secondary btn-sm" data-placement="top">Load Position Preset</button>
                        </span>
                            <span data-toggle="modal" data-target="#removePreset">
                            <button type="button" class="btn btn-danger btn-sm" data-placement="top">Remove Position Preset</button>
                        </span>
                        @endif
                </div>
            </div>
            @if(Auth::user()->isAbleTo('events'))
            <br>
            <div class="card">
                <div class="card-header">
                    <h3>
                        Send Event Reminder
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <a href="/dashboard/admin/events/send-reminder/{{ $event->id }}" class="btn btn-primary btn-sm"><i class="fa fa-envelope"></i>&nbsp;Send Reminder</a>
                    </div>
                    <br>
                    <p>
                        Send an event reminder email to all members registered for this event. Note: the email system prevents members from being notitified
                        multiple times. You may trigger this reminder at any time to notify late sign-ups after the initial reminder was sent out.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/dashboard/controllers/events" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
    @if(Auth::user()->isAbleTo('events'))
        <a href="/dashboard/admin/events/edit/{{ $event->id }}" class="btn btn-success">Edit</a>
        <a href="/dashboard/admin/events/delete/{{ $event->id }}" class="btn btn-danger">Delete</a>
    @endif

	@if(Auth::user()->isAbleTo('events'))
		<div class="modal fade" id="savePreset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Position Preset Name</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					{!! Form::open(['action' => ['AdminDash@setEventPositionPreset', $event->id]]) !!}
					@csrf
					<div class="modal-body">
						{!! Form::label('name', 'Name') !!}
						{!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Save Position Preset</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="modal fade" id="loadPreset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Load Position Preset</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					{!! Form::open(['action' => ['AdminDash@retrievePositionPreset', $event->id]]) !!}
					@csrf
					<div class="modal-body">
						{!! Form::label('p_id', 'Position Preset') !!}
						{!! Form::select('p_id', $presets, null, ['placeholder' => 'Select Preset', 'class' => 'form-control']) !!}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Load Position Preset</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="modal fade" id="removePreset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Remove Position Preset</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					{!! Form::open(['action' => ['AdminDash@deletePositionPreset', $event->id]]) !!}
					@csrf
					<div class="modal-body">
						{!! Form::label('p_id', 'Position Preset') !!}
						{!! Form::select('p_id', $presets, null, ['placeholder' => 'Select Preset', 'class' => 'form-control']) !!}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Remove Position Preset</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="modal fade" id="manualAssign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Manual Assign Position</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					{!! Form::open(['action' => ['AdminDash@manualAssign', $event->id]]) !!}
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									{!! Form::label('controller', 'Controller Name') !!}
									{!! Form::select('controller', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
								</div>
								<div class="col-sm-6">
									{!! Form::label('position', 'Position') !!}
									{!! Form::select('position', $positions->pluck('name', 'id'), null, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
								</div>
							</div>
						</div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    {!! Form::label('position_detail', 'Position or sector ID assigned') !!}
                                    {!! Form::text('position_detail', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									{!! Form::label('start_time', 'Start Time (Zulu)') !!}
									{!! Form::text('start_time', null, ['placeholder' => $event->start_time, 'class' => 'form-control']) !!}
								</div>
								<div class="col-sm-6">
									{!! Form::label('end_time', 'End Time (Zulu)') !!}
									{!! Form::text('end_time', null, ['placeholder' => $event->end_time, 'class' => 'form-control']) !!}
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Assign Position</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	@endif
</div>
    {{Html::script(asset("js/event_view.js"))}}
@endsection
