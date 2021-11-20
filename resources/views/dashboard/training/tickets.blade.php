@extends('layouts.dashboard')

@section('title')
Training Tickets
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Training Tickets</h2>
    &nbsp;
</div>
<br>
<div class="container">
    @if($search_result != null)
        <a class="btn btn-primary" href="/dashboard/training/tickets/new?id={{ $search_result->id }}">Submit New Training Ticket</a>
    @else
        <a class="btn btn-primary" href="/dashboard/training/tickets/new">Submit New Training Ticket</a>
    @endif
    <br><br>
    <h5>Search Training Tickets:</h5>
    {!! Form::open(['url' => '/dashboard/training/tickets/search']) !!}
        <div class="row">
            <div class="col-sm-3">
                {!! Form::text('cid', null, ['placeholder' => 'Search by CID', 'class' => 'form-control']) !!}
            </div>
            <div class="col-sm-1">
                <button class="btn btn-primary" action="submit">Search</button>
            </div>
            <div class="col-sm-1">
    {!! Form::close() !!}
    <center>OR</center>
    {!! Form::open(['url' => '/dashboard/training/tickets/search']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::select('cid', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
            </div>
            <div class="col-sm-1">
                <button class="btn btn-primary" action="submit">Search</button>
            </div>
        </div>
    {!! Form::close() !!}

    @if($search_result != null)

        <hr>
        <h5>Showing Training Tickets for {{ $search_result->full_name }} ({{ $search_result->id }})</h5>
        <br>




        <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#s1" role="tab" data-toggle="tab" style="color:black">S1</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#s2" role="tab" data-toggle="tab" style="color:black">S2</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#s3" role="tab" data-toggle="tab" style="color:black">S3</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#c1" role="tab" data-toggle="tab" style="color:black">C1</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#other" role="tab" data-toggle="tab" style="color:black">Other</a>
        </li>
    </ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="s1">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Training Date</th>
                    <th scope="col">Trainer Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Session Type</th>
					<th scope="col">Session ID</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">INS/MTR Comments</th>
                </tr>

				@php($transition_date = \Carbon\Carbon::parse('11/12/2021'))

                @if($tickets->count() > 0)
                    @foreach($tickets as $t)
                        @if($t->position > 6 && $t->position < 22)
                        <tr>
                            <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                            <td>{{ $t->trainer_name }}</td>
                            <td>{{ $t->position_name }}</td>
                            <td>{{ $t->type_name }}</td>
							<td>{{ $t->session_name }}</td>
                            <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                            <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                            <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                        </tr>

                    @elseif($t->position > 99 && $t->position < 109)
                    <tr>
                        <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                        <td>{{ $t->trainer_name }}</td>
                        <td>{{ $t->position_name }}</td>
                        <td>{{ $t->type_name }}</td>
						<td>{{ $t->session_name }}</td>
                        <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                    </tr>

                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No training tickets found.</td>
                    </tr>
                @endif




            </thead>
        </table>
    </div>


    <div role="tabpanel" class="tab-pane" id="s2">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Training Date</th>
                    <th scope="col">Trainer Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Session Type</th>
					<th scope="col">Session ID</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">INS/MTR Comments</th>
                </tr>
                @if($tickets->count() > 0)
                  @foreach($tickets as $t)
                    @if($t->position > 21 && $t->position < 31)
                    <tr>
                        <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                        <td>{{ $t->trainer_name }}</td>
                        <td>{{ $t->position_name }}</td>
                        <td>{{ $t->type_name }}</td>
						<td>{{ $t->session_name }}</td>
                        <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                    </tr>

                @elseif($t->position > 108 && $t->position < 114)
                <tr>
                    <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                    <td>{{ $t->trainer_name }}</td>
                    <td>{{ $t->position_name }}</td>
                    <td>{{ $t->type_name }}</td>
					<td>{{ $t->session_name }}</td>
                    <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                    <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                    <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                </tr>
                    @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No training tickets found.</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="s3">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Training Date</th>
                    <th scope="col">Trainer Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Session Type</th>
					<th scope="col">Session ID</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">INS/MTR Comments</th>
                </tr>
                @if($tickets->count() > 0)
                    @foreach($tickets as $t)
                      @if($t->position > 30 && $t->position < 42)
                    <tr>
                        <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                        <td>{{ $t->trainer_name }}</td>
                        <td>{{ $t->position_name }}</td>
                        <td>{{ $t->type_name }}</td>
						<td>{{ $t->session_name }}</td>
                        <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                    </tr>

                @elseif(($t->position > 113 && $t->position < 120) || ($t->position == 123))
                <tr>
                    <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                    <td>{{ $t->trainer_name }}</td>
                    <td>{{ $t->position_name }}</td>
                    <td>{{ $t->type_name }}</td>
					<td>{{ $t->session_name }}</td>
                    <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                    <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                    <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No training tickets found.</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="c1">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Training Date</th>
                    <th scope="col">Trainer Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Session Type</th>
					<th scope="col">Session ID</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">INS/MTR Comments</th>
                </tr>
                @if($tickets->count() > 0)
                    @foreach($tickets as $t)
                      @if($t->position > 41 && $t->position < 48)
                    <tr>
                        <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                        <td>{{ $t->trainer_name }}</td>
                        <td>{{ $t->position_name }}</td>
                        <td>{{ $t->type_name }}</td>
						<td>{{ $t->session_name }}</td>
                        <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                    </tr>

                @elseif($t->position > 119 && $t->position < 122)
                <tr>
                    <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                    <td>{{ $t->trainer_name }}</td>
                    <td>{{ $t->position_name }}</td>
                    <td>{{ $t->type_name }}</td>
					<td>{{ $t->session_name }}</td>
                    <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                    <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                    <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                </tr>
                    @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No training tickets found.</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="other">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Training Date</th>
                    <th scope="col">Trainer Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Session Type</th>
					<th scope="col">Session ID</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">INS/MTR Comments</th>
                </tr>
                @if($tickets->count() > 0)
                    @foreach($tickets as $t)
                    @if($t->position < 7)

                        <tr>
                            <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                            <td>{{ $t->trainer_name }}</td>
                            <td>{{ $t->position_name }}</td>
                            <td>{{ $t->type_name }}</td>
							<td>{{ $t->session_name }}</td>
                            <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                            <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                            <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>
                        </tr>
                    
                    @elseif($t->position > 121)
                    <tr>
                        <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                        <td>{{ $t->trainer_name }}</td>
                        <td>{{ $t->position_name }}</td>
                        <td>{{ $t->type_name }}</td>
						<td>{{ $t->session_name }}</td>
                        <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>

                    </tr>

                    @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No training tickets found.</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>
</div>
        {!! $tickets->appends(['id' => $search_result->id])->render() !!}
    @endif
</div>

@endsection
