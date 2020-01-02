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
               {!! $feedback->links() !!}         
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
                        @if(isset($tickets))
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
                @if(!isset($tickets))
                    <p>No training tickets found.</p>
                @endif
            </div>
            @if(isset($tickets))
                {!! $tickets->links() !!}
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h4>My Information</h4>
            <br>
            <p><b>CID:</b> {{ Auth::id() }}</p>
            <p><b>Name:</b> {{ Auth::user()->full_name }}</p>
            <p><b>Rating:</b> {{ Auth::user()->rating_long }}</p>
            <p><b>Email:</b> {{ Auth::user()->email }} <a style="color:inherit" href="https://cert.vatsim.net/vatsimnet/newmail.php" target="_blank" data-toggle="tooltip" title="Click Here to Update (It may take up to an hour for changes to be reflected)"><i class="fas fa-info-circle"></i></a></p>
            @if(!$discord)
                <a href="/discord/login" class="btn btn-success"><i class="fab fa-discord"></i>&nbsp;Login To Discord</a>
            @else
                <p><b>Discord Username:</b> {{ $discord->discord_username  }} <a style="color:inherit" href="/discord/logout" data-toggle="tooltip" title="Click Here to Logout of Discord"><i class="fas fa-info-circle"></i></a></p>
                <a class="btn btn-danger disabled"><i class="fab fa-discord"></i>&nbsp;Already Logged Into Discord</a>
            @endif
        </div>
        <div class="col-sm-2">
        </div>
        <div class="col-sm-4">
            <center>
                <h4>My Recent Activity:</h4>
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @if($personal_stats->total_hrs < 1)
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
                                    <i>No Training Since 12/04/2018</i>
                                @endif
                            </b></p>
                        </li>
                        @if(Auth::user()->can('train'))
                            <li class="list-group-item" style="background-color:lightgray">
                                <h5>Last Training Session Given:</h5>
                                <p><b>
                                    @if(isset($last_training_given))
                                        {{ $last_training_given->last_training }}
                                    @else
                                        <i>No Training Given Since 12/04/2018</i>
                                    @endif
                                </b></p>
                            </li>
                        @endif
                    </ul>
                </div>
            </center>
        </div>
    </div>
</div>
@endsection
