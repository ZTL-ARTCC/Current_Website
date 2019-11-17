@extends('layout')

@section('title')
Profile
@endsection

@section('content')

<div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    My Profile
                </h3>
            </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <div class="form-group">
                     <label class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                        <p class="form-control-static">{{Auth::user()->fname}} {{Auth::user()->lname}}</p>
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{Auth::user()->email}}</p>
                            <span id="helpBlock" class="help-block">Click <a
                                 href="http://cert.vatsim.net/vatsimnet/newmail.php">here</a> to change.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Rating</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{Auth::user()->RatingLong}}<br></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Receive Broadcast Emails</label>
                            <div class="col-sm-10">
                                <span id="toggleOptin" style="font-size:1.8em;">
                                    <i class="toggle-icon fa fa-toggle-{{ Auth::user()->flag_broadcastOptedIn ? "on text-success" : "off text-danger"}} "></i>
                                    <i class="spinner-icon fa fa-spinner fa-spin" style="display:none;"></i>
                            </span>
                            <p class="help-block">To receive emails from the ZTL'S mass emailing system, you must
                                opt-in by
                                clicking on the toggle switch above. <br>This only affects the mass emailing system of
                                ARTCCs
                                that choose to use this response.<br><strong>This setting does not affect
                                account-related emails like transfer requests and exam results/assignments.</strong>
                            </p>
                        </div>
                    </div>
                   
            <center>
                <h4>My Recent Activity:</h4>
                <div class="card">
                    <ul class="list-group list-group-flush" style="  float: left; position: absolute;top: 260px;: 0; right: 500px;width: 200px;height: 100px;">
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
        
                </form>
            </div>
</div>




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
   
</div>
@endsection
