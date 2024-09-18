@extends('layouts.dashboard')

@section('title')
Profile
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/profile.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'My Profile'])

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
                                    <td><center>{{ str_limit($f->comments, 25, '...') }}</center></td>
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
                                    <td>
                                        <center>
                                            <a href="/dashboard/controllers/ticket/{{ $t->id }}">{{ $t->date }}</a>
                                            <br />
                                            @if(!$t->student_comments)
                                                <span class="badge badge-danger">Awaiting Your Comments</span>
                                            @else
                                                <span class="badge badge-success">Comments Submitted</span>
                                            @endif
                                        </center>
                                    </td>
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
            <h4>My Training Appointments</h4>
            <div class="table">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">Lesson Type</th>
                            <th scope="col">Instructor/Mentor</th>
                            <th scope="col">View/Modify</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($ea_appointments) > 0)
                            @foreach($ea_appointments as $ea_appointment)
                                <tr>
                                    <td scope="col">{{ $ea_appointment->res_date }}</td>
                                    <td scope="col">{{ $ea_appointment->res_time }} ET</td>
                                    <td scope="col">{{ $ea_appointment->service_description }}</td>
                                    <td scope="col">{{ $ea_appointment->staff_name }}</td>
                                    <td scope="col"><a href="https://scheduling.ztlartcc.org/index.php/booking/reschedule/{{ $ea_appointment->link_token }}?name_first={{ Auth::user()->fname }}&name_last={{ Auth::user()->lname }}&email={{ Auth::user()->email }}&cid={{ Auth::id() }}" target="_blank" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="View"><i class="fas fa-edit fa-fw"></i></a></td>
                                </tr>   
                            @endforeach
                        @else
                            <tr>
                                <td scope="col" colspan="5">No appointments found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
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
            <p><b>Email:</b> {{ Auth::user()->email }} <a class="info-tooltip" href="https://my.vatsim.net/user/email" target="_blank" data-toggle="tooltip" title="Click Here to Update (It may take up to an hour for changes to be reflected)"><i class="fas fa-info-circle"></i></a></p>
            <p><b>Name Privacy:</b> {{ Auth::user()->name_privacy == 1 ? 'Enabled' : 'Disabled' }} <a class="info-tooltip" href="https://www.vatusa.net/my/profile" target="_blank" data-toggle="tooltip" title="Click Here to Update"><i class="fas fa-info-circle"></i></a></p>
            {{ html()->form()->route('updateInfo', [Auth::id()])->open() }}
            @csrf
                <div class="row">
                    <div class="col-5"><b>TS3 UID: <a class="info-tooltip" href="#" data-toggle="tooltip" title="In TeamSpeak 3, go to Tools->Identifies. Paste your 'Unique ID' here for bot integration"><i class="fas fa-info-circle"></i></a></b></div>
                    <div class="col-7">{{ html()->text('ts3', Auth::user()->ts3)->class(['form-control']) }}</div>
                </div>

                <div class="row mt-2">
                    <div class="col-5">
                        <b>Timezone: <a class="info-tooltip" href="#" data-toggle="tooltip"
                                        title="Times will be shown in this timezone, along with Zulu. For Zulu, select UTC. If you don't know what to pick here, look up 'tzdb identifier list' or ask in Discord for help."><i
                                        class="fas fa-info-circle"></i></a></b>
                    </div>

                    <div class="col-7">
                        <p>
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

                <div class="row mb-4">
                    <div class="col-4">
                        <button class="btn btn-success" type="submit">Save Profile</button>
                    </div>

                    @toggle('discord_role_updater')
                        @if(Auth::user()->discord)
                            <div class="col-4">
                                <a href="/dashboard/controllers/profile/discord" class="btn btn-success" type="button">Update Discord Roles</a>
                            </div>profile.
                        @else
                            <div class="col-4">
                                <a href="#" data-toggle="tooltip" title="No Discord ID Found: Ensure your Discord ID is linked to your VATUSA account. The roster may take time to refresh. You can manually update your role in the ZTL Discord server in the meantime." class="btn btn-secondary" type="button">Update Discord Role</a>
                            </div>
                        @endif
                    @endtoggle
                </div>
                {{ html()->form()->close() }}


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
                        @if($personal_stats->total_hrs < 3)
                            <li class="list-group-item hours-danger">
                                <h5>Hours this Quarter:</h5>
                                <p><b>{{ $personal_stats->total_hrs }}</b></p>
                            </li>
                        @else
                            <li class="list-group-item hours-success">
                                <h5>Hours this Quarter:</h5>
                                <p><b>{{ $personal_stats->total_hrs }}</b></p>
                            </li>
                        @endif
                        <li class="list-group-item tng-received">
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
                            <li class="list-group-item tng-given">
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
                <p>You may opt out at any time by using the slider shown on the profile page.</p>
                <br>
                <i>Please check the following check boxes if you would like to continue.</i>
                <hr>
                {{ html()->form()->route('optIn')->open() }}
                <div class="form-group">
                    {{ html()->checkbox('opt', false, '1') }}
                    <label for="opt" class="form-label">I agree to recieve mass emails from the vZTL ARTCC.</label>
                    <br>
                    {{ html()->checkbox('privacy', false, '1') }}
                    <label for="privacy" class="form-label">I have read and agree to the vZTL ARTCC Privacy Policy.</label>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ url()->current() }}" class="btn btn-secondary">Close</a>
                <button type="submit" class="btn btn-success">Confirm Selection</button>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</div>

@endsection
