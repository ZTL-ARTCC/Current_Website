@extends('layouts.dashboard')

@section('title')
Dashboard
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Controller Dashboard'])

<div class="container-fluid">
    @if($announcement->body != null)
        <div class="alert alert-success">
            {!! $announcement->body !!}
            <hr>
            <p class="small"><i>Last updated by {{ $announcement->staff_name }} on {{ $announcement->update_time }}</i></p>
        </div>
        <hr>
    @endif
    <div class="row clocks">
        <div class="col-sm-3">
            <div class="card card-body">
                <h5>Zulu/UTC Time Now:</h5>
                <iframe src="https://www.clocklink.com/html5embed.php?clock=043&timezone=GMT&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" allowTransparency="true"></iframe>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body">
                <h5>Eastern Time Now:</h5>
                <iframe src="https://www.clocklink.com/html5embed.php?clock=043&timezone=EST&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" allowTransparency="true"></iframe>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body">
                <h5>Central Time Now:</h5>
                <iframe src="https://www.clocklink.com/html5embed.php?clock=043&timezone=CST&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" allowTransparency="true"></iframe>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body">
                <h5>Pacific Time Now:</h5>
                <iframe src="https://www.clocklink.com/html5embed.php?clock=043&timezone=PST&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" allowTransparency="true"></iframe>
            </div>
        </div>
    </div>
    @include('inc.notifications')
    <hr>
    <h4 class="text-center"><i>Controller Dashboard Quicklinks</i></h4>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <a class="btn btn-secondary btn-block" href="/dashboard/controllers/profile">My Profile</a>
            @if(Auth::user()->isAbleTo('staff'))
                <a class="btn btn-secondary btn-block" href="/dashboard/admin/announcement">Edit Announcement</a>
            @endif
        </div>
        <div class="col-sm-3">
            <a class="btn btn-secondary btn-block" href="https://scheduling.ztlartcc.org?name_first={{ Auth::user()->fname }}&name_last={{ Auth::user()->lname }}&email={{ Auth::user()->email }}&cid={{ Auth::id() }}" target="_blank">Schedule a Training Session</a>
            @if(Auth::user()->isAbleTo('staff'))
                <a class="btn btn-secondary btn-block" href="http://mail.ztlartcc.org" target="_blank">Email</a>
            @endif
        </div>
        <div class="col-sm-3">
            <button data-toggle="modal" data-target="#reportBug" class="btn btn-secondary btn-block">Report a Bug</button>
            @if(Auth::user()->isAbleTo('staff'))
                <a class="btn btn-secondary btn-block" href="/dashboard/admin/calendar">Manage Calendar/News</a>
            @endif
        </div>
        <div class="col-sm-3">
            <a class="btn btn-secondary btn-block" href="/">Return to Main Website</a>
            @if(Auth::user()->isAbleTo('staff'))
                <a class="btn btn-secondary btn-block" href="/dashboard/admin/airports">Manage Airports</a>
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h4 class="text-center"><i class="fas fa-newspaper"></i> News</h4>
            @if(count($news) > 0)
                @foreach($news as $c)
                    <p>{{ $c->date }} - <b>{{ $c->title }}</b> <a href="/dashboard/controllers/calendar/view/{{ $c->id }}">(View)</a></p>
                @endforeach
            @else
                <p class="text-center"><i>No news to show.</i></p>
            @endif
        </div>
        <div class="col-sm-4">
            <h4 class="text-center"><i class="fas fa-calendar-alt"></i> Calendar</h4>
            @if(count($calendar) > 0)
                @foreach($calendar as $c)
                    <p>{{ $c->date }} ({{ $c->time }}) - <b>{{ $c->title }}</b> <a href="/dashboard/controllers/calendar/view/{{ $c->id }}">(View)</a></p>
                @endforeach
            @else
                <p class="text-center"><i>No calendar events to show.</i></p>
            @endif
        </div>
        <div class="col-sm-4">
            <h4 class="text-center"><a href="/dashboard/controllers/events" style="color:inherit;text-decoration:none"><i class="fas fa-plane"></i> Events</a></h4>
			<div id="eventCarousel" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					@if($events->count() > 0)
					    @foreach($events as $e)
					        <div class="carousel-item @if ($loop->first) active @endif">
						        <a href="/dashboard/controllers/events/view/{{ $e->id }}">
							        <img src="{{ $e->banner_path }}" class="d-block w-100 rounded" alt="{{ $e->name }}" />
						        </a>
					        </div>
					    @endforeach
					@else
					    <div class="carousel-item active">
						    <div class="d-block w-100 h-100 d-flex align-items-center bg-dark rounded" style="min-height:200px">
							    <h5 class="text-light w-100 text-center">No events scheduled</br>Please check back soon!</h5>
						    </div>
					    </div>
					@endif
				</div>
				<a class="carousel-control-prev" href="#eventCarousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#eventCarousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
        </div>
    </div>
    <hr>
    <h4 class="text-center"><i class="fa fa-plane"></i> Flights Currently Within ZTL Airspace</h4>
    <div class="table">
        <table class="table table-bordered table-sm">
            <thead class="text-center">
                <th scope="col">Callsign</th>
                <th scope="col">Pilot Name</th>
                <th scope="col">Aircraft Type</th>
                <th scope="col">Departure</th>
                <th scope="col">Arrival</th>
                <th scope="col">Route</th>
            </thead>
            <tbody>
                @if($flights->count() > 0)
                    @foreach($flights as $c)
                        <tr class="text-center">
                            <td>{{ $c->callsign }}</td>
                            <td>{{ $c->pilot_name }}</td>
                            <td>{{ $c->type }}</td>
                            <td>{{ $c->dep }}</td>
                            <td>{{ $c->arr }}</td>
                            <td>{{ str_limit($c->route, 50) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="6"><i>No Pilots in ZTL Airspace</i></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <hr>
    @php
        $no_winner = "<h4>No Winner Selected</h4><h5>Check back for updates!</h5>";
    @endphp
    <h2 class="text-center"><i class="fa fa-microphone bronze"></i> Bronze Mic Award <i class="fa fa-microphone bronze"></i></h2>
	<div class="row text-center">
		<div class="col-sm-6">
            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-8">
        			<h4>Winner for {{ $pmonth_words }}</h4>
        			<div class="card card-body bronze-bg">
        				@if($pwinner != null)
        					<h4><b>{{ $pwinner->name }}</b></h4>
        					<h5>With {{ $pwinner->month_hours }} hours!</h5>
        				@else
                            {!! $no_winner !!}
                        @endif
                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
			</div>
		</div>
		<div class="col-sm-6">
            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-8">
        			<h4>Winner for {{ $month_words }}</h4>
        			<div class="card card-body bronze-bg">
        				@if($winner != null)
        					<h4><b>{{ $winner->name }}</b></h4>
        					<h5>With {{ $winner->month_hours }} hours!</h5>
        				@else
                            {!! $no_winner !!}
        				@endif
                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
			</div>
		</div>
    </div>
    @toggle('local-hero')
        <hr>
        <h2 class="text-center"><i class="fas fa-trophy text-primary"></i> Local Hero Award <i class="fas fa-trophy text-primary"></i></h2>
	    <div class="row text-center">
		    <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-8">
        			    <h4>Winner for {{ $pmonth_words }}</h4>
        			    <div class="card card-body bg-primary">
        				    @if($pwinner_local != null)
        					    <h4><b>{{ $pwinner_local->name }}</b></h4>
        					    <h5>With {{ $pwinner_local->month_hours }} hours!</h5>
        				    @else
                                {!! $no_winner !!}
        				    @endif
                        </div>
                        <h6>{{ $pmonth_challenge_description }}</h6>
                        <div class="col-sm-2">
                        </div>
                    </div>
			    </div>
		    </div>
		    <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-8">
        			    <h4>Winner for {{ $month_words }}</h4>
        			    <div class="card card-body bg-primary">
        				    @if($winner_local != null)
        					    <h4><b>{{ $winner_local->name }}</b></h4>
        					    <h5>With {{ $winner_local->month_hours }} hours!</h5>
        				    @else
                                {!! $no_winner !!}
        				    @endif
                        </div>
                        <h6>{{ $month_challenge_description }}</h6>
                        <div class="col-sm-2">
                        </div>
                    </div>
			    </div>
		    </div>
        </div>
    @endtoggle
    <hr>
    <h2 class="text-center"><i class="fa fa-microphone pyrite"></i> Pyrite Mic Award for {{ $lyear }} <i class="fa fa-microphone pyrite"></i></h2>
    <div class="row text-center">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            <div class="card card-body pyrite-bg">
                @if($pyrite != null)
                    <h4><b>{{ $pyrite->name }}</b></h4>
                    <h5>With {{ $pyrite->year_hours }} hours!</h5>
                @else
                    {!! $no_winner !!}
                @endif
            </div>
        </div>
        <div class="col-sm-4">
        </div>
    </div>

    <div class="modal fade" id="reportBug" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report a Bug</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{ html()->form()->route('reportBug')->open() }}
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="url">Intended URL</label>
                                {{ html()->text('url', null)->placeholder('Paste the Intended URL Here')->class(['form-control']) }}
                            </div>
                            <div class="col-sm-6">
                                <label for="error">Error Received (If Applicable)</label>
                                {{ html()->text('error', null)->placeholder('Paste Error Here, If Applicable')->class(['form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="desc">Brief Description of Bug</label>
                        {{ html()->textarea('desc', null)->placeholder('Please be brief but specific with details regarding the bug.')->class(['form-control']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button action="submit" class="btn btn-success">Send</button>
                </div>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</div>
@endsection
