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
                        @if($feedback->count() >=1 )
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
        <div class="col">
            <h4>My Setmore Reservations</h4>
            <div class="table">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">Lesson Type</th>
                            <th scope="col">Instructor/Mentor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($setmore_appointments) > 0)
                            @foreach($setmore_appointments as $sm_res)
                                <tr>
                                    <td scope="col">{{ $sm_res->res_date }}</td>
                                    <td scope="col">{{ $sm_res->res_time }} ET</td>
                                    <td scope="col">{{ $sm_res->service_description }}</td>
                                    <td scope="col">{{ $sm_res->staff_name }}</td>
                                </tr>   
                            @endforeach
                        @else
                            <tr>
                                <td scope="col" colspan="4">No Setmore reservations found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <p>Appointments will show here approximately 30 minutes after booking.</p>
            </div>
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
            {!! Form::open(['action' => ['ControllerDash@updateInfo', Auth::id()]]) !!}
            @csrf
                <div class="row">
                    <div class="col-5"><b>TS3 UID: <a style="color:inherit" href="#" data-toggle="tooltip" title="In TeamSpeak 3, go to Tools->Identifies. Paste your 'Unique ID' here for bot integration"><i class="fas fa-info-circle"></i></a></b></div>
                    <div class="col-7">{!! Form::text('ts3', Auth::user()->ts3, ['class' => 'form-control']) !!}</div>
                </div>

                <div class="row mt-2">
                    <div class="col-5">
                        <b>Timezone: <a style="color:inherit" href="#" data-toggle="tooltip"
                                        title="Times will be shown in this timezone, along with Zulu. For Zulu, select UTC. If you don't know what to pick here, look up 'tzdb identifier list' or ask in Discord for help."><i
                                        class="fas fa-info-circle"></i></a></b>
                    </div>

                    <div class="col-7">
                        <p>
                            <!-- Form::select default isn't working (because it doesn't set autocomplete=off), so I'm doing it manually -->
                            <select autocomplete="off" name="timezone" class="form-control">
                                @foreach (DateTimeZone::listIdentifiers() as $timezone)
                                    <option autocomplete="off" value="{{ $timezone }}"
                                            @if ($timezone == Auth::user()->timezone)
                                                selected="selected"
                                            @endif
                                    >{{ $timezone }}</option>
                                @endforeach
                            </select>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <button class="btn btn-success" type="submit">Save Profile</button>
                    </div>
                </div>
                {!! Form::close() !!}

                Receive Broadcast Emails?
                &nbsp;
                @if(Auth::user()->opt == 1)
                   <span data-toggle="modal" data-target="#unOpt">
                          <label class="switch">
                              <input type="checkbox" checked>
                            <span class="slider round"></span>
                            </label>
                        </span>
               @else
                   <span data-toggle="modal" data-target="#Opt">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </span>
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
                        @if(Auth::user()->isAbleTo('train'))
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

<div class="modal fade" id="unOpt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opt Out of Broadcast Emails?</h5>
            </div>
            <br>
            <div class="container">
                <p>Please note that opting out of broadcast emails will only prevent you from receiving broadcast emails issued from staff. Personalized emails (both automated and issued by staff) will not be affected. If you have any questions, please contact the ATM at <a href="mailto:atm@ztlartcc.org">atm@ztlartcc.org</a>.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ url()->current() }}" class="btn btn-secondary">Close</a>
                <a href="/dashboard/opt/out" class="btn btn-success">Confirm Selection</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Opt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opt Into Broadcast Emails?</h5>
            </div>
            <br>
            <div class="container">
                <p>Opting into emails will only affect the recieving of mass emails. If you elect to opt into emails, you agree to recieve mass emails sent to groups of members of the vZTL ARTCC. This selection will not affect the reception of personalized emails (both automated and issued by staff) for example, training ticket emails. If you have any questions, please contact the ATM at <a href="mailto:atm@ztlartcc.org">atm@ztlartcc.org</a>.</p>
                <p>You may opt out at any time by using the slider shown at the top of the controller dashboard at all times.</p>
                <br>
                <i>Please check the following check boxes if you would like to continue.</i>
                <hr>
                {!! Form::open(['action' => 'ControllerDash@optIn']) !!}
                <div class="form-group">
                    {!! Form::checkbox('opt', '1', false) !!}
                    {!! Form::label('opt', 'I agree to recieve mass emails from the vZTL ARTCC.', ['class' => 'form-label']) !!}
                    <br>
                    {!! Form::checkbox('privacy', '1', false) !!}
                    {!! Form::label('privacy', 'I have read and agree to the vZTL ARTCC Privacy Policy.', ['class' => 'form-label']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ url()->current() }}" class="btn btn-secondary">Close</a>
                <button type="submit" class="btn btn-success">Confirm Selection</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
