@extends('layouts.dashboard')

@section('title')
View Event
@endsection

@section('content')
@include('inc.header', ['title' => 'Event Information and Signup'])

<div class="container">
    @if($event->banner_path != null)
    <div class="row mb-4">
        <div class="col-sm-*">
            <div class="card">
                <div class="card-header p-4">
                    <img src="{{ $event->banner_path }}" width="100%" alt="{{ $event->name }}">
                </div>
            </div>
        </div>
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
                                                                                 data-bs-toggle="tooltip"
                                                                                 title="Showing times in {{ $timezone }}. You can change this on your profile."><i
                                        class="fas fa-info-circle"></i></a>)
                        </h5>
                        <h5><b>Type:</b>
                            @if(Auth::user()->isAbleTo('events'))
                                @if($event->type == App\Event::$TYPES["LOCAL"])
                                    Local Event
                                @elseif($event->type == App\Event::$TYPES["VERIFIED_SUPPORT"])
                                    Support Event
                                @else
                                    Unverified Support Event
                                    <a style="color:inherit" href="#" data-bs-toggle="tooltip" title="Verify by setting the Event Type to 'Support Event' on the Edit Event page."><i class="fas fa-info-circle"></i></a>
                                @endif
                            @else
                                @if($event->type == App\Event::$TYPES["LOCAL"])
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
                                <span class="float-end" data-bs-toggle="modal" data-bs-target="#manualAssign">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-placement="top">Manual Assign</button>
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

                                                <a style="color:inherit" href="#" data-bs-toggle="tooltip" title="
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
                                                        <button data-bs-toggle="modal" data-bs-target="#addrequest{{ $r->id }}" type="button" class="btn btn-success btn-sm simple-tooltip" data-bs-placement="top" title="Assign Position">
                                                            <i class="fas fa-check fa-fw"></i>
                                                        </button>

                                                        <a href="/dashboard/controllers/events/view/{{ $r->id }}/un-signup" class="btn btn-danger btn-sm" title="Delete Request">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </a>

                                                        @if($r->remarks != null)
                                                            <button data-bs-toggle="modal" data-bs-target="#remarks{{ $r->id }}" type="button" class="btn btn-info btn-sm simple-tooltip" data-bs-placement="top" title="View Remarks">
                                                                <i class="fas fa-info-circle fa-fw"></i>
                                                            </button>
                                                        @else
                                                            <button disabled data-bs-toggle="modal" data-bs-target="#remarks{{ $r->id }}" type="button" class="btn btn-info btn-sm disabled" data-bs-placement="top" title="No Remarks Provided">
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
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><i>Remarks submitted by {{ $r->controller_name }}:</i></p>
                                                        <p>{{ $r->remarks }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    {{ html()->form()->route('assignPosition', [$r->id])->open() }}
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="controller_name">Controller Name</label>
                                                                    {{ html()->text('controller_name', $r->controller_name)->class(['form-control'])->attributes(['disabled']) }}
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="position">Position</label>
                                                                    {{ html()->select('position', $positions->pluck('name', 'id'), $r->position_id)->class(['form-select']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label for="position_detail">Position or sector ID assigned</label>
                                                                    {{ html()->text('position_detail', null)->class(['form-control']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="start_time">Start Time (Zulu)</label>
                                                                    {{ html()->text('start_time', $r->start_time)->placeholder($event->start_time)->class(['form-control']) }}
                                                                </div>
                                                                <div class="col-sm-6">
                                                                <label for="end_time">End Time (Zulu)</label>
                                                                    {{ html()->text('end_time', $r->end_time)->placeholder($event->end_time)->class(['form-control']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close
                                                        </button>
                                                        <button action="submit" class="btn btn-success">Assign
                                                            Position
                                                        </button>
                                                    </div>
                                                    {{ html()->form()->close() }}
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
                                <a href="/dashboard/admin/events/toggle-reg/{{ $event->id }}" class="btn btn-success btn-simple-tooltip float-end" data-bs-toggle="tooltip" title="Open Registration"><i class="fas fa-check"></i></a>
                            @else
                                <a href="/dashboard/admin/events/toggle-reg/{{ $event->id }}" class="btn btn-danger btn-simple-tooltip float-end" data-bs-toggle="tooltip" title="Close Registration"><i class="fas fa-times"></i></a>
                            @endif
                            @toggle('event_assignment_toggle')
                                @if($event->show_assignments)
                                    <a href="/dashboard/admin/events/toggle-show-assignments/{{ $event->id }}" class="btn btn-danger btn-simple-tooltip float-end me-2" data-bs-toggle="tooltip" title="Hide Assignments"><i class="fas fa-eye-slash"></i></a>
                                @else
                                    <a href="/dashboard/admin/events/toggle-show-assignments/{{ $event->id }}" class="btn btn-success btn-simple-tooltip float-end me-2" data-bs-toggle="tooltip" title="Show Assignments"><i class="fas fa-eye"></i></a>
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
                                                               style="color:inherit" data-bs-toggle="tooltip"
                                                               title="Unassign Controller"><i class="fas fa-times"></i></a>
                                                            &nbsp;
                                                        @endif
                                                        <b>
                                                            {{ $c->controller_name }}
                                                            &nbsp;
                                                            @if(Auth::user()->isAbleTo('events'))
                                                                @if($c->no_show == 1)
                                                                    <a href="/dashboard/admin/events/noshow/unmark/{{ $c->id }}"
                                                                        class="text-danger" data-bs-toggle="tooltip"
                                                                        title="Unmark No-Show"><i class="fas fa-user-tag"></i></a>
                                                                @else
                                                                    <a href="/dashboard/admin/events/noshow/mark/{{ $c->id }}"
                                                                        class="text-success" data-bs-toggle="tooltip"
                                                                        title="Mark No-Show"><i class="fas fa-user-check"></i></a>
                                                                @endif
                                                            @endif
                                                            <br>
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

                                                                    <a style="color:inherit" href="#" data-bs-toggle="tooltip" title="
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
                                                   class="btn btn-danger btn-sm simple-tooltip" data-bs-toggle="tooltip"
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
                            {{ html()->form()->route('signupForEvent')->open() }}
                            @csrf
                            @if($event->reg == 1 && Auth::user()->canEvents == 1)
                                {{ html()->hidden('event_id', $event->id) }}
                                <div class="form-group">
                                    @if($your_registration1)
                                        {{ html()->hidden('yr1', $your_registration1->id) }}
                                        <div class="row">
                                            @if($your_registration1->status == App\Event::$STATUSES["HIDDEN"])
                                                <div class="col-sm-5">
                                                    {{ html()->select('num1', $positions->pluck('name', 'id'), $your_registration1->position_id)->placeholder('Position')->class(['form-select']) }}
                                                </div>
                                                <div class="col-sm-3">
                                                    {{ html()->text('start_time1', $your_registration1->start_time)->placeholder($event->start_time)->class(['form-control']) }}
                                                </div>
                                                <div class="col-sm-3">
                                                    {{ html()->text('end_time1', $your_registration1->end_time)->placeholder($event->end_time)->class(['form-control']) }}
                                                </div>
                                            @else
                                                <div class="col-sm-5">
                                                    @if(toggleEnabled('event_assignment_toggle') && ! $event->show_assignments)
                                                        {{ html()->select('num1', $positions->pluck('name', 'id'), null)->attributes(['disabled'])->placeholder('Pending...')->class(['form-select']) }}
                                                    @else
                                                        {{ html()->select('num1', $positions->pluck('name', 'id'), $your_registration1->position_id)->attributes(['disabled'])->placeholder('Choice 1')->class(['form-select']) }}
                                                    @endif
                                                </div>
                                                <div class="col-sm-3">
                                                    {{ html()->text('start_time1', $your_registration1->start_time)->attributes(['disabled'])->placeholder($event->start_time)->class(['form-control']) }}
                                                </div>
                                                <div class="col-sm-3">
                                                    {{ html()->text('end_time1', $your_registration1->end_time)->attributes(['disabled'])->placeholder($event->end_time)->class(['form-control']) }}
                                                </div>
                                                {{ html()->hidden('timedata', $event->start_time.';'.$event->end_time.';'.timeToLocal($event->start_time, Auth::user()->timezone).';'.timeToLocal($event->end_time, Auth::user()->timezone))->id('timedata') }}
                                                {{ html()->hidden('timezone', 'Zulu')->attributes(['autocomplete' => 'off'])->id('timezone') }}
                                            @endif
                                        </div>
                                    @else
                                        {{ html()->hidden('yr1', null) }}
                                        <div class="row mb-2">
                                            <div class="col-sm-5 mt-1">
                                                {{ html()->select('num1', $positions->pluck('name', 'id'), null)->attributes(['autocomplete' => 'off'])->placeholder('Desired Position')->class(['form-select']) }}
                                            </div>
                                        </div>
                                        <div class="row row-cols-lg-auto g-3 align-items-center">
                                            {{ html()->hidden('timedata', $event->start_time.';'.$event->end_time.';'.timeToLocal($event->start_time, Auth::user()->timezone).';'.timeToLocal($event->end_time, Auth::user()->timezone))->id('timedata') }}
                                            <div class="col-12">
                                                <label for="start_time1" class="form-label">Available from:</label>
                                            </div>
                                            <div class="col-12">
                                                {{ html()->text('start_time1', null)->attributes(['size' => '4', 'autocomplete' => 'off'])->placeholder($event->start_time)->class(['form-control', 'col-sm-2', 'me-2'])->id('start_time1') }}
                                            </div>
                                            <div class="col-12">
                                                <label for="end_time1" class="form-label">-</label>
                                            </div>
                                            <div class="col-12">
                                                {{ html()->text('end_time1', null)->attributes(['size' => '4', 'autocomplete' => 'off'])->placeholder($event->end_time)->class(['form-control', 'col-sm-2', 'me-2']) }}
                                            </div>
                                            <div class="col-12">
                                                {{ html()->select('timezone', ['Zulu', 'Local'], 'Zulu')->attributes(['autocomplete' => 'off'])->class(['form-select', 'col-sm-3'])->id('timezone') }}
                                            </div>
                                        </div>
                                        <div class="row my-2 align-items-center">
                                            <div class="col-12">
                                                {{ html()->textarea('remarks', null)->placeholder('Specific position requests/additional information (optional)')->class(['form-control', 'textarea-no-resize', 'col-sm-11', 'pe-2'])->attributes(['rows' => '3', 'maxlength' => '1024']) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group inline">
                                    @if(!$your_registration1)
                                        <button type="submit" class="btn btn-success">Submit Request</button>
                                    @else
                                        <a href="/dashboard/controllers/events/view/{{ $your_registration1->id }}/un-signup"
                                           class="btn btn-danger mt-2">Delete your Signup</a>
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
                            {{ html()->form()->close() }}
                        @else
                            <p>No positions added.</p>
                        @endif

                        <hr title="Position Management">

                        @if(Auth::user()->isAbleTo('events'))
                            <br>
                            {{ html()->form()->route('addPosition', [$event->id])->open() }}
                            @csrf
                            <div class="row">
                                <div class="col-sm-10">
                                    {{ html()->text('name', null)->placeholder('New Position')->class(['form-control']) }}
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-success simple-tooltip" data-bs-toggle="tooltip"
                                            title="Add Position"><i class="fas fa-check"></i></button>
                                </div>
                            </div>
                            {{ html()->form()->close() }}
                            <br>
                            <span data-bs-toggle="modal" data-bs-target="#savePreset">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-placement="top">Save as Position Preset</button>
                        </span>
                            <span data-bs-toggle="modal" data-bs-target="#loadPreset">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-placement="top">Load Position Preset</button>
                        </span>
                            <span data-bs-toggle="modal" data-bs-target="#removePreset">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-placement="top">Remove Position Preset</button>
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
            <br>
            <div class="card">
                <div class="card-header">
                    <h3>
                        Event Statistics Report
                    </h3>
                </div>
                <div class="card-body">
                    @if($event->has_stat_report_run)
                        {{ html()->text('tracking_airports', $event->tracking_airports)->class(['form-control', 'mb-3'])->disabled()->placeholder('Airports for Tracking for Event Statistics') }}
                        <div class="text-center">
                            <a href="/dashboard/admin/events/statistics/{{ $event->id }}" class="btn btn-success">Report Ready</a>
                            <a href="/dashboard/admin/events/statistics/rerun/{{ $event->id }}" class="btn btn-danger">Delete Report and Rerun</a>
                        </div>
                    @else
                        {{ html()->form()->route('updateEventTrackingAirports', [$event->id])->open() }}
                            <div class="row">
                                <div class="col-sm-10">
                                    {{ html()->text('tracking_airports', $event->tracking_airports)->class(['form-control'])->placeholder('Airports for Tracking for Event Statistics') }}
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-success simple-tooltip" data-bs-toggle="tooltip"
                                            title="Update Airports"><i class="fas fa-check"></i></button>
                                </div>
                            </div>
                        {{ html()->form()->close() }}
                        <p class="small mb-3">Enter the FAA identifier of the airports to include in the statistics report separated by commas with no spaces. Example: "ATL,CLT,BHM"</p>
                        <div class="text-center">
                            @if(is_null($event->tracking_airports))
                                <button class="btn btn-danger" disabled>No Airports Listed for Report</button>
                            @else
                                <button class="btn btn-success" disabled>Report Available 24 Hours After Event Ends</button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/dashboard/controllers/events" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
    @if(Auth::user()->isAbleTo('events'))
        <a href="/dashboard/admin/events/edit/{{ $event->id }}" class="btn btn-success">Edit</a>
        @if($event->vatsim_id)
            <button data-bs-toggle="modal" data-bs-target="#denylistEvent" class="btn btn-danger">Delete</button>
        @else
            <a href="/dashboard/admin/events/delete/{{ $event->id }}" class="btn btn-danger">Delete</a>
        @endif
    @endif

	@if(Auth::user()->isAbleTo('events'))
		<div class="modal fade" id="savePreset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Position Preset Name</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
                    {{ html()->form()->route('setEventPositionPreset', [$event->id])->open() }}
					@csrf
					<div class="modal-body">
                        <label for="name">Name</label>
						{{ html()->text('name', null)->placeholder('Name')->class(['form-control']) }}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Save Position Preset</button>
					</div>
					{{ html()->form()->close() }}
				</div>
			</div>
		</div>
		<div class="modal fade" id="loadPreset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Load Position Preset</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
                    {{ html()->form()->route('retrievePositionPreset', [$event->id])->open() }}
					@csrf
					<div class="modal-body">
                        <label for="p_id">Position Preset</label>
						{{ html()->select('p_id', $presets, null, ['placeholder' => 'Select Preset'])->class(['form-select']) }}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Load Position Preset</button>
					</div>
					{{ html()->form()->close() }}
				</div>
			</div>
		</div>
		<div class="modal fade" id="removePreset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Remove Position Preset</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
                    {{ html()->form()->route('deletePositionPreset', [$event->id])->open() }}
					@csrf
					<div class="modal-body">
                        <label for="p_id">Position Preset</label>
						{{ html()->select('p_id', $presets, null, ['placeholder' => 'Select Preset'])->class(['form-select']) }}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Remove Position Preset</button>
					</div>
					{{ html()->form()->close() }}
				</div>
			</div>
		</div>
		<div class="modal fade" id="manualAssign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Manual Assign Position</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
                    {{ html()->form()->route('manualAssign', [$event->id])->open() }}
    				@csrf
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
                                    <label for="controller">Controller Name</label>
									{{ html()->select('controller', $controllers, null, ['placeholder' => 'Select Controller'])->class(['form-select']) }}
								</div>
								<div class="col-sm-6">
                                    <label for="position">Position</label>
									{{ html()->select('position', $positions->pluck('name', 'id'), null, ['placeholder' => 'Select Position'])->class(['form-select']) }}
								</div>
							</div>
						</div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="position_detail">Position or sector ID assigned</label>
                                    {{ html()->text('position_detail', null)->class(['form-control']) }}
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
                                    <label for="start_time">Start Time (Zulu)</label>
									{{ html()->text('start_time', null)->placeholder($event->start_time)->class(['form-control']) }}
								</div>
								<div class="col-sm-6">
                                    <label for="end_time">End Time (Zulu)</label>
									{{ html()->text('end_time', null)->placeholder($event->end_time)->class(['form-control']) }}
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Assign Position</button>
					</div>
					{{ html()->form()->close() }}
				</div>
			</div>
		</div>
	@endif
    <div class="modal fade" id="denylistEvent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Denylist Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Would you like to denylist this event?</p>
                    <p>This will prevent this event from being reinstated on the next update command.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/dashboard/admin/events/delete/{{ $event->id }}" class="btn btn-danger">Delete</a>
                    <a href="/dashboard/admin/events/delete/{{ $event->id }}?denylist=true" class="btn btn-danger">Delete and Denylist Event</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{mix('js/event_view.js')}}"></script>
@endsection
