@extends('layouts.dashboard')

@section('title')
Profile
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>My Profile</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <center><h4>My Feedback:</h4></center>
            <div class="table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><center>Position</center></th>
                            <th scope="col"><center>Result</center></th>
                            <th scope="col"><center>Comments</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($feedback->count() > 0)
                            @foreach($feedback as $f)
                                <tr>
                                    <td><center><a data-toggle="tooltip" title="View Details" href="/dashboard/controllers/profile/feedback-details/{{ $f->id }}">{{ $f->position }}</a></center></td>
                                    <td><center>{{ $f->service_level_text }}</center></td>
                                    <td><center>{{ str_limit($f->comments, 50, '...') }}</center></td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
                        @else
                    </tbody>
                </table>
                            <p>No feedback found.</p>
                        @endif
            </div>
        </div>
        <div class="col-sm-6">
            <center><h4>My Training Tickets:</h4></center>
            <div class="table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><center>Date</center></th>
                            <th scope="col"><center>Trainer</center></th>
                            <th scope="col"><center>Position</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($tickets->count() > 0)
                            @foreach($tickets as $t)
                                <tr>
                                    <td><center><a href="/dashboard/controllers/ticket/{{ $t->id }}">{{ $t->date }}</a></center></td>
                                    <td><center>{{ $t->trainer_name }}</center></td>
                                    <td><center>{{ $t->position_name }}</center></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if($tickets->count() == 0)
                    <p>No training tickets found.</p>
                @endif
            </div>
            {!! $tickets->links() !!}
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            <center>
                <h4>My Recent Activity:</h4>
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @if($personal_stats->total_hrs < 3)
                            <li class="list-group-item" style="background-color:#E6B0AA">
                                <h5>Hours this Month:</h5>
                                <p><b>{{ $personal_stats->total_hrs }}</b></p>
                            </li>
                        @else
                            <li class="list-group-item" style="background-color:#A9DFBF">
                                <h5>Hours this Month:</h5>
                                <p><b>{{ $personal_stats->total_hrs }}</b></p>
                            </li>
                        @endif
                        <li class="list-group-item" style="background-color:aqua">
                            <h5>Last Training Session Received:</h5>
                            <p><b>
                                @if($last_training != null)
                                    {{ $last_training->last_training }}
                                @else
                                    <i>No Training Since 12/25/2018</i>
                                @endif
                            </b></p>
                        </li>
                        @if(Auth::user()->can('train'))
                            <li class="list-group-item" style="background-color:lightgray">
                                <h5>Last Training Session Given:</h5>
                                <p><b>
                                    @if($last_training != null)
                                        {{ $last_training_given->last_training }}
                                    @else
                                        <i>No Training Given Since 12/25/2018</i>
                                    @endif
                                </b></p>
                            </li>
                        @endif
                    </ul>
                </div>
            </center>
        </div>
        <div class="col-sm-4">
        </div>
    </div>
</div>
@endsection
