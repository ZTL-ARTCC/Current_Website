@extends('layouts.dashboard')

@section('title')
Update Controller
@endsection

@section('content')
@include('inc.header', ['title' => '<i class="fas fa-user"></i>&nbsp;Update ' . $user->full_name . ' (' . $user->id. ')'])

<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#profile" role="tab" data-toggle="tab" style="color:black"><i class="fas fa-id-card"></i>&nbsp;Member Profile</a>
        </li>
        @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train') || Auth::user()->hasRole('ec'))
        <li class="nav-item">
            <a class="nav-link" href="#certifications" role="tab" data-toggle="tab" style="color:black"><i class="fas fa-graduation-cap"></i>&nbsp;Controller Certifications</a>
        </li>
        @endif
        @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('events'))
        <li class="nav-item">
            <a class="nav-link" href="#events" role="tab" data-toggle="tab" style="color:black"><i class="fa-solid fa-chart-line"></i>&nbsp;Event Participation</a>
        </li>
        @endif
    </ul>
    {{ html()->form()->route('AdminDash@updateController', [$user->id]) }}
    @csrf
    @php
        $roster_disable = 'disabled';
        if(Auth::user()->isAbleTo('roster')) {
            $roster_disable = null;
        }
        $train_config_disable = 'disabled';
        if(Auth::user()->isAbleTo('roster') || Auth::user()->hasRole('ata')) {
           $train_config_disable = null;
        }
        $events_disable = 'disabled';
        if(Auth::user()->isAbleTo('roster') || Auth::user()->hasRole('ec')) {
            $events_disable = null;
        }
    @endphp
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile">
            <br>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('CID', 'cid') }}
                        {{ html()->text('cid', $user->id)->class(['form-control'])->attributes(['disabled']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Rating', 'rating') }}
                        {{ html()->text('rating', $user->rating_long)->class(['form-control'])->attributes(['disabled']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('First Name', 'fname') }}
                        {{ html()->text('fname', $user->fname)->class(['form-control'])->attributes(['disabled']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Last Name', 'lname') }}
                        {{ html()->text('lname', $user->lname)->class(['form-control'])->attributes(['disabled']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Email', 'email') }}
                        {{ html()->text('email', $user->email)->class(['form-control'])->attributes(['disabled']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Initials', 'initials') }}
                        {{ html()->text('initials', $user->initials)->class(['form-control'])->attributes([$roster_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        @if($user->visitor == 1)
                            {{ html()->label('Visitor From', 'visitor_from') }}
                            {{ html()->text('visitor_from', $user->visitor_from)->class(['form-control'])->attributes([$roster_disable]) }}
                            {{ html()->hidden('status', $user->status) }}
                        @else
                            {{ html()->label('Status', 'status') }}
                            {{ html()->select('status', $user->user_status, $user->status)->class(['form-control'])->attributes([$roster_disable]) }}
                        @endif
                    </div>
                    <div class="col-sm-6">
                        *Note: Read-only roster data is sync'd with the VATSIM CERT database nightly
                    </div>
                </div>
            </div>
            <hr>
            <h6><i class="fas fa-building"></i>&nbsp;Facility Staff Settings</h6>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Facility Staff', 'staff') }}
                        {{ html()->select('staff', $user->facility_staff, $user->staff_position)->class(['form-control')->attributes([$roster_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Events Staff', 'events_staff') }}
                        {{ html()->select('events_staff', $user->events_staff, $user->events_position)->class(['form-control'])->attributes([$events_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Training Staff', 'training') }}
                        {{ html()->select('training', $user->training_staff, $user->train_position)->class(['form-control'])->attributes([$roster_disable]) }}
                    </div>
                    @if($user->hasRole('mtr') || $user->hasRole('ins'))
                    <div class="col-sm-6">
                        {{ html()->label('Training Level', 'max') }}
                        {{ html()->select('max', $user->training_level, $user->max)->class(['form-control'])->attributes([$train_config_disable]) }}
                    </div>
                    @endif
                </div>
            </div>
            <hr>
            <h6><i class="fas fa-user-cog"></i>&nbsp;Account Settings</h6>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-10">
                        @php
                            $allow_training = ($user->canTrain == 1) ? true : false;
                            $is_visitor = ($user->visitor == 1) ? true : false;
                            $allow_events = ($user->canEvents == 1) ? true : false;
                            $api_exempt = ($user->api_exempt == 1) ? true : false;
                        @endphp
                        {{ html()->label('Allow Training?', 'canTrain') }}
                        {{ html()->checkbox('canTrain', $allow_training, 1)->attributes([$roster_disable]) }}
                    </div>
                    <div class="col-sm-10">
                        {{ html()->label('Visitor?', 'visitor') }}
                        {{ html()->checkbox('visitor', $is_visitor, 1)->attributes(['disabled']) }}
                        @if($user->visitor == 1)
                            <a href="/dashboard/admin/roster/visit/remove/{{ $user->id }}">(Remove from Roster)</a>
                        @endif
                    </div>
                    <div class="col-sm-10">
                        {{ html()->label('Allow Signing up for Events?', 'canEvents') }}
                        {{ html()->checkbox('canEvents', $allow_events, 1)->attributes([$roster_disable]) }}
                    </div>
                    @if($user->visitor != 1)
                    <div class="col-sm-10">
                        {{ html()->label('Exempt from VATUSA API Roster Update?', 'api_exempt') }}
                        {{ html()->checkbox('api_exempt', $api_exempt, 1)->attributes([$roster_disable]) }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1">
                    <button class="btn btn-success" type="submit"><i class="fas fa-save"></i>&nbsp;Save</button>
                </div>

                <div class="col-sm-1">
                    <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fas fa-undo"></i>&nbsp;Cancel</a>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="certifications">
            <br>
            <h6><span class="badge badge-warning text-light">New!</span>&nbsp;This control now complies with GCAP requirements.<br>Please review the latest version of ZTL 3120.4 prior to updating a controller's certifications.</h6>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        @php
                            $solo_disable = $unres_gnd_disable = $unres_twr_disable = $unres_app_disable = $center_disable = 'disabled';
                            $clt_del_gnd_disable = $clt_twr_disable = $clt_app_disable = $atl_disable = $atl_app_disable = 'disabled';
                            if(Auth::user()->isAbleTo('roster')) {
                                $solo_disable = $unres_gnd_disable = $unres_twr_disable = $unres_app_disable = $center_disable = null;
                                $clt_del_gnd_disable = $clt_twr_disable = $clt_app_disable = $atl_disable = $atl_app_disable = null;
                            }
                            elseif (Auth::user()->isAbleTo('train') && is_numeric(Auth::user()->max)) {
                                $unres_gnd_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_UNRES_GND')) ? null : 'disabled';
                                $solo_disable = (Auth::user()->max > Auth::user()->getMagicNumber('TRAIN_UNRES_GND')) ? null : 'disabled';
                                $clt_del_gnd_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_CLT_DEL_GND')) ? null : 'disabled';
                                $unres_twr_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_UNRES_TWR')) ? null : 'disabled';
                                $clt_twr_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_CLT_TWR')) ? null : 'disabled';
                                $atl_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_ATL_TWR')) ? null : 'disabled';
                                $unres_app_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_UNRES_APP')) ? null : 'disabled';
                                $clt_app_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_CLT_APP')) ? null : 'disabled';
                                $atl_app_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_ATL_APP')) ? null : 'disabled';
                                $center_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_CTR')) ? null : 'disabled';
                            }
                        @endphp
                        {{ html()->hidden('del', $user->del) }}
                        {{ html()->label('Unrestricted Ground/Clearance Delivery', 'gnd') }}
                        {{ html()->select('gnd', $user->uncertified_certified, $user->gnd)->class(['form-control'])->attributes([$unres_gnd_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Unrestricted Tower', 'twr') }}
                        {{ html()->select('twr', $user->Uncertified_solo_certified, $user->twr)->class(['form-control'])->attributes([$unres_twr_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Unrestricted Approach', 'app') }}
                        {{ html()->select('app', $user->uncertified_solo_certified, $user->app)->class(['form-control'])->attributes([$unres_app_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Center Certification', 'ctr') }}
                        {{ html()->select('ctr', $user->uncertified_solo_certified, $user->ctr)->class(['form-control'])->attributes([$center_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Unrestricted Solo Certifications (list facility IDs)', 'twr_solo_fields') }}
                        {{ html()->text('twr_solo_fields', $user->twr_solo_fields)->class(['form-control'])->attributes(['maxlength' => 255, $solo_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Solo Expiration Date', 'twr_solo_expires')->class(['form-label']) }}
                        {{ html()->text('solo_expires', $user->solo_exp)->class(['form-control'])->attributes(['disabled']) }}
                    </div>
                </div>
            </div>
            <hr>
            <h6><i class="fas fa-level-up-alt"></i>&nbsp;Tier 1 Facility Certifications</h6>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Charlotte Clearance Delivery', 'clt_del') }}
                        {{ html()->select('clt_del', $user->uncertified_certified, $user->clt_del)->class(['form-control'])->attributes([$clt_del_gnd_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Charlotte Ground', 'clt_gnd') }}
                        {{ html()->select('clt_gnd', $user->uncertified_certified, $user->clt_gnd)->class(['form-control'])->attributes([$clt_del_gnd_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Charlotte Tower', 'clt_twr') }}
                        {{ html()->select('clt_twr', $user->uncertified_certified, $user->clt_twr)->class(['form-control'])->attributes([$clt_twr_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Charlotte Approach', 'clt_app') }}
                        {{ html()->select('clt_app', $user->uncertified_certified, $user->clt_app)->class(['form-control'])->attributes([$clt_app_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Atlanta Clearance Delivery', 'atl_del') }}
                        {{ html()->select('atl_del', $user->uncertified_certified, $user->atl_del)->class(['form-control'])->attributes([$atl_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Atlanta Ground', 'atl_gnd') }}
                        {{ html()->select('atl_gnd', $user->uncertified_certified, $user->atl_gnd)->class(['form-control'])->attributes([$atl_disable]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Atlanta Tower', 'atl_twr') }}
                        {{ html()->select('atl_twr', $user->uncertified_certified, $user->atl_twr)->class(['form-control'])->attributes([$atl_disable]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Atlanta (A80) Approach', 'atl_app') }}
                        {{ html()->select('atl_app', $user->uncertified_certified_a80, $user->atl_app)->class(['form-control'])->attributes([$atl_app_disable]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1">
                    <button class="btn btn-success" type="submit"><i class="fas fa-save"></i>&nbsp;Save</button>
                </div>

                <div class="col-sm-1">
                    <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fas fa-undo"></i>&nbsp;Cancel</a>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="events">
            <br>
            <h5>Controler Event Participation Tracking</h5>
            <div class="row mb-2">
                <div class="col-6">
                    <div class="card p-3">
                        <h5 class="card-title">Stats Last 12-Months</h5>
                        <div class="card-body">
                            Event Participation: {{ $event_stats->events_total_12mo }}<br>
                            Event Hours Logged: {{ $event_stats->hours_total_12mo }}<br>
                            Event No-Shows: {{ $event_stats->no_shows_12mo }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card p-3">
                        <h5 class="card-title">Stats Lifetime</h5>
                        <div class="card-body">
                            Event Participation: {{ $event_stats->events_total }}<br>
                            Event Hours Logged: {{ $event_stats->hours_total }}<br>
                            Event No-Shows: {{ $event_stats->no_shows }}
                        </div>
                    </div>
                </div>            
            </div>
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>Date</th>
                        <th>Event Name</th>
                        <th>Position<br>Assigned</th>
                        <th>Connection<br>Log</th>
                        <th>Time Logged<br>(hours)</th>
                        <th>No Show?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr class="text-center">
                            <td>{{ $event->event_date }}</td>
                            <td><a href="/dashboard/controllers/events/view/{{ $event->id }}" alt="Link to event" target="_blank"></a>{{ $event->event_name }}</td>
                            <td>{{ $event->position_assigned }}</td>
                            <td>
                                @foreach ($event->connection as $connection)
                                    {{ $connection->callsign }} ({{ $connection->start }}-{{ $connection->end }}) <br>
                                @endforeach
                            </td>
                            <td>{{ $event->time_logged }}</td>
                            <td>
                                @if($event->no_show == 1)
                                    <span class="text-danger" data-toggle="tooltip" title="Marked No-Show"><i class="fas fa-user-tag"></i></span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if (count($events) == 0)
                        <tr class="text-center">
                            <td colspan="6">No event history found for this controller.</td>
                        </tr>
                    @endif        
                </tbody>
            </table>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>
<script src="{{asset('js/roster.js')}}">
@endsection
