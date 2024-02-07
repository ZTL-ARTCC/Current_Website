@extends('layouts.dashboard')

@section('title')
Dashboard
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Controller Dashboard</h2>
    &nbsp;
</div>
<br>

<div class="container-fluid">
    @if($announcement->body != null)
        <div class="alert alert-success">
            {!! $announcement->body !!}
            <hr>
            <p class="small"><i>Last updated by {{ $announcement->staff_name }} on {{ $announcement->update_time }}</i></p>
        </div>
        <hr>
    @endif
    <div class="row">
        <div class="col-sm-3">
            <div class="card card-body" style="background-color:lightgrey">
                <center><h5>Zulu/UTC Time Now:</h5></center>
                <center><iframe style="pointer-events: none" src="https://www.clocklink.com/html5embed.php?clock=043&timezone=GMT&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" width="200" height="64" allowTransparency="true"></iframe></center>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body" style="background-color:lightgrey">
                <center><h5>Eastern Time Now:</h5></center>
                <center><iframe style="pointer-events: none" src="https://www.clocklink.com/html5embed.php?clock=043&timezone=EST&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" width="200" height="64" allowTransparency="true"></iframe></center>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body" style="background-color:lightgrey">
                <center><h5>Central Time Now:</h5></center>
                <center><iframe style="pointer-events: none" src="https://www.clocklink.com/html5embed.php?clock=043&timezone=CST&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" width="200" height="64" allowTransparency="true"></iframe></center>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body" style="background-color:lightgrey">
                <center><h5>Pacific Time Now:</h5></center>
                <center><iframe style="pointer-events: none" src="https://www.clocklink.com/html5embed.php?clock=043&timezone=PST&color=black&size=180&Title=&Message=&Target=&From=2019,1,1,0,0,0&Color=black" frameborder="0" width="200" height="64" allowTransparency="true"></iframe></center>
            </div>
        </div>
    </div>
    @include('inc.notifications')
    <hr>
    <center><h4><i>Controller Dashboard Quicklinks</i></h4></center>
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
            <center><h4><i class="fas fa-newspaper"></i> News</h4></center>
            @if(count($news) > 0)
                @foreach($news as $c)
                    <p>{{ $c->date }} - <b>{{ $c->title }}</b> <a href="/dashboard/controllers/calendar/view/{{ $c->id }}">(View)</a></p>
                @endforeach
            @else
                <center><i><p>No news to show.</p></i></center>
            @endif
        </div>
        <div class="col-sm-4">
            <center><h4><i class="fas fa-calendar-alt"></i> Calendar</h4></center>
            @if(count($calendar) > 0)
                @foreach($calendar as $c)
                    <p>{{ $c->date }} ({{ $c->time }}) - <b>{{ $c->title }}</b> <a href="/dashboard/controllers/calendar/view/{{ $c->id }}">(View)</a></p>
                @endforeach
            @else
                <center><i><p>No calendar events to show.</p></i></center>
            @endif
        </div>
        <div class="col-sm-4">
            <center><h4><a href="/dashboard/controllers/events" style="color:inherit;text-decoration:none"><i class="fas fa-plane"></i> Events</a></h4></center>
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
    <center><h4><i class="fa fa-plane"></i> Flights Currently Within ZTL Airspace</h4></center>
    <div class="table">
        <table class="table table-bordered table-sm">
            <thead>
                <th scope="col"><center>Callsign</center></th>
                <th scope="col"><center>Pilot Name</center></th>
                <th scope="col"><center>Aircraft Type</center></th>
                <th scope="col"><center>Departure</center></th>
                <th scope="col"><center>Arrival</center></th>
                <th scope="col"><center>Route</center></th>
            </thead>
            <tbody>
                @if($flights->count() > 0)
                    @foreach($flights as $c)
                        <tr>
                            <td><center>{{ $c->callsign }}</center></td>
                            <td><center>{{ $c->pilot_name }}</center></td>
                            <td><center>{{ $c->type }}</center></td>
                            <td><center>{{ $c->dep }}</center></td>
                            <td><center>{{ $c->arr }}</center></td>
                            <td><center>{{ str_limit($c->route, 50) }}</center></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6"><center><i>No Pilots in ZTL Airspace</i></center></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="6"><div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $flights_update }}Z</div></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <center><h2><i class="fa fa-microphone" style="color:#C9AE5D"></i> Bronze Mic Award <i class="fa fa-microphone" style="color:#C9AE5D"></i></h2></center>
	<div class="row">
		<div class="col-sm-6">
            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-8">
        			<center><h4>Winner for {{ $pmonth_words }}</h4></center>
        			<div class="card card-body" style="background-color:#C9AE5D">
        				@if($pwinner != null)
        					<center><h4><b>{{ $pwinner->name }}</b></h4></center>
        					<center><h5>With {{ $pwinner->month_hours }} hours!</h5></center>
        				@else
        					<center><h4>No Winner Was Chosen</h4></center>
        					<center><h5>Check back for updates!</h5></center>
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
        			<center><h4>Most Recent Winner ({{ $month_words }})</h4></center>
        			<div class="card card-body" style="background-color:#C9AE5D">
        				@if($winner != null)
        					<center><h4><b>{{ $winner->name }}</b></h4></center>
        					<center><h5>With {{ $winner->month_hours }} hours!</h5></center>
        				@else
        					<center><h4>No Winner Was Chosen</h4></center>
        					<center><h5>Check back for updates!</h5></center>
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
        <center><h2><i class="fas fa-trophy text-primary"></i> Local Hero Award <i class="fas fa-trophy text-primary"></i></h2></center>
	    <div class="row">
		    <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-8">
        			    <center><h4>Winner for {{ $pmonth_words }}</h4></center>
        			    <div class="card card-body bg-primary">
        				    @if($pwinner_local != null)
        					    <center><h4><b>{{ $pwinner_local->name }}</b></h4></center>
        					    <center><h5>With {{ $pwinner_local->month_hours }} hours!</h5></center>
        				    @else
        					    <center><h4>No Winner Was Chosen</h4></center>
        					    <center><h5>Check back for updates!</h5></center>
        				    @endif
                        </div>
                        <center><h6>{{ $pmonth_challenge_description }}</h6></center>
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
        			    <center><h4>Most Recent Winner ({{ $month_words }})</h4></center>
        			    <div class="card card-body bg-primary">
        				    @if($winner_local != null)
        					    <center><h4><b>{{ $winner_local->name }}</b></h4></center>
        					    <center><h5>With {{ $winner_local->month_hours }} hours!</h5></center>
        				    @else
        					    <center><h4>No Winner Was Chosen</h4></center>
        					    <center><h5>Check back for updates!</h5></center>
        				    @endif
                        </div>
                        <center><h6>{{ $month_challenge_description }}</h6></center>
                        <div class="col-sm-2">
                        </div>
                    </div>
			    </div>
		    </div>
        </div>
    @endtoggle
    <hr>
    <center><h2><i class="fa fa-microphone" style="color:#FFDF00"></i> Pyrite Mic Award for 20{{ $lyear }} <i class="fa fa-microphone" style="color:#FFDF00"></i></h2></center>
    <div class="row">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            <div class="card card-body" style="background-color:#FFDF00">
                @if($pyrite != null)
                    <center><h4><b>{{ $pyrite->name }}</b></h4></center>
                    <center><h5>With {{ $pyrite->year_hours }} hours!</h5></center>
                @else
                    <center><h4>No Winner Was Chosen</h4></center>
                    <center><h5>Check back for updates!</h5></center>
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
                {!! Form::open(['action' => 'ControllerDash@reportBug']) !!}
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                {!! Form::label('url', 'Intended URL') !!}
                                {!! Form::text('url', null, ['placeholder' => 'Paste the Intended URL Here', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('error', 'Error Received (If Applicable)') !!}
                                {!! Form::text('error', null, ['placeholder' => 'Paste Error Here, If Applicable', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('desc', 'Brief Description of Bug') !!}
                        {!! Form::textArea('desc', null, ['placeholder' => 'Please be brief but specific with details regarding the bug.', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button action="submit" class="btn btn-success">Send</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
