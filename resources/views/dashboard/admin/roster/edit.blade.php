@extends('layouts.dashboard')

@section('title')
Update Controller
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2><i class="fas fa-user"></i>&nbsp;Update {{ $user->full_name }} ({{ $user->id }})</h2>
    &nbsp;
</div>
<br>
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
            <a class="nav-link disabled" href="#events" role="tab" data-toggle="tab" style="color:black"><i class="fa-solid fa-chart-line"></i>&nbsp;Event Participation</a>
        </li>
        @endif
    </ul>
    {!! Form::open(['action' => ['AdminDash@updateController', $user->id]]) !!}
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
                        {!! Form::label('cid', 'CID') !!}
                        {!! Form::text('cid', $user->id, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('rating', 'Rating') !!}
                        {!! Form::text('rating', $user->rating_long, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('fname', 'First Name') !!}
                        {!! Form::text('fname', $user->fname, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('lname', 'Last Name') !!}
                        {!! Form::text('lname', $user->lname, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', $user->email, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('initials', 'Initials') !!}
                        {!! Form::text('initials', $user->initials, ['class' => 'form-control', $roster_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        @if($user->visitor == 1)
                            {!! Form::label('visitor_from', 'Visitor From') !!}
                            {!! Form::text('visitor_from', $user->visitor_from, ['class' => 'form-control', $roster_disable]) !!}
                            {!! Form::hidden('status', $user->status) !!}
                        @else
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', $user->user_status, $user->status, ['class' => 'form-control', $roster_disable]) !!}
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
                        {!! Form::label('staff', 'Facility Staff') !!}
                        {!! Form::select('staff', $user->facility_staff, $user->staff_position, ['class' => 'form-control', $roster_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('events_staff', 'Events Staff') !!}
                        {!! Form::select('events_staff', $user->events_staff, $user->events_position, ['class' => 'form-control', $events_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('training', 'Training Staff') !!}
                        {!! Form::select('training', $user->training_staff, $user->train_position, ['class' => 'form-control', $roster_disable]) !!}
                    </div>
                    @if($user->hasRole('mtr') || $user->hasRole('ins'))
                    <div class="col-sm-6">
                        {!! Form::label('max', 'Training Level') !!}
                        {!! Form::select('max', $user->training_level, $user->max, ['class' => 'form-control', $train_config_disable]) !!}
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
                        {!! Form::label('canTrain', 'Allow Training?') !!}
                        {!! Form::checkbox('canTrain', 1, $allow_training, [$roster_disable]) !!}
                    </div>
                    <div class="col-sm-10">
                        {!! Form::label('visitor', 'Visitor?') !!}
                        {!! Form::checkbox('visitor', 1, $is_visitor, ['disabled']) !!}
                        @if($user->visitor == 1)
                            <a href="/dashboard/admin/roster/visit/remove/{{ $user->id }}">(Remove from Roster)</a>
                        @endif
                    </div>
                    <div class="col-sm-10">
                        {!! Form::label('canEvents', 'Allow Signing up for Events?') !!}
                        {!! Form::checkbox('canEvents', 1, $allow_events, [$roster_disable]) !!}
                    </div>
                    @if($user->visitor != 1)
                    <div class="col-sm-10">
                        {!! Form::label('api_exempt', 'Exempt from VATUSA API Roster Update?') !!}
                        {!! Form::checkbox('api_exempt', 1, $api_exempt, [$roster_disable]) !!}
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
                                $atl_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_ATL')) ? null : 'disabled';
                                $unres_app_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_UNRES_APP')) ? null : 'disabled';
                                $clt_app_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_CLT_APP')) ? null : 'disabled';
                                $atl_app_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_ATL_APP')) ? null : 'disabled';
                                $center_disable = (Auth::user()->max >= Auth::user()->getMagicNumber('TRAIN_CTR')) ? null : 'disabled';
                            }
                        @endphp
                        {!! Form::hidden('del', $user->del) !!}
                        {!! Form::label('gnd', 'Unrestricted Ground/Clearance Delivery') !!}
                        {!! Form::select('gnd', $user->uncertified_certified, $user->gnd, ['class' => 'form-control', $unres_gnd_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('twr', 'Unrestricted Tower') !!}
                        {!! Form::select('twr', $user->Uncertified_solo_certified, $user->twr, ['class' => 'form-control', $unres_twr_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('app', 'Unrestricted Approach') !!}
                        {!! Form::select('app', $user->uncertified_solo_certified, $user->app, ['class' => 'form-control', $unres_app_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('ctr', 'Center Certification') !!}
                        {!! Form::select('ctr', $user->uncertified_solo_certified, $user->ctr, ['class' => 'form-control', $center_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('twr_solo_fields', 'Unrestricted Solo Certifications (list facility IDs)') !!}
                        {!! Form::text('twr_solo_fields', $user->twr_solo_fields, ['class' => 'form-control','maxlength' => 255, $solo_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('twr_solo_expires', 'Solo Expiration Date', ['class' => 'form-label']) !!}
                        {!! Form::text('solo_expires', $user->solo_exp, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <hr>
            <h6><i class="fas fa-level-up-alt"></i>&nbsp;Tier 1 Facility Certifications</h6>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('clt_del', 'Charlotte Clearance Delivery') !!}
                        {!! Form::select('clt_del', $user->uncertified_certified, $user->clt_del, ['class' => 'form-control', $clt_del_gnd_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('clt_gnd', 'Charlotte Ground') !!}
                        {!! Form::select('clt_gnd', $user->uncertified_certified, $user->clt_gnd, ['class' => 'form-control', $clt_del_gnd_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('clt_twr', 'Charlotte Tower') !!}
                        {!! Form::select('clt_twr', $user->uncertified_certified, $user->clt_twr, ['class' => 'form-control', $clt_twr_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('clt_app', 'Charlotte Approach') !!}
                        {!! Form::select('clt_app', $user->uncertified_certified, $user->clt_app, ['class' => 'form-control', $clt_app_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('atl_del', 'Atlanta Clearance Delivery') !!}
                        {!! Form::select('atl_del', $user->uncertified_certified, $user->atl_del, ['class' => 'form-control', $atl_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('atl_gnd', 'Atlanta Ground') !!}
                        {!! Form::select('atl_gnd', $user->uncertified_certified, $user->atl_gnd, ['class' => 'form-control', $atl_disable]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('atl_twr', 'Atlanta Tower') !!}
                        {!! Form::select('atl_twr', $user->uncertified_certified, $user->atl_twr, ['class' => 'form-control', $atl_disable]) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('atl_app', 'Atlanta (A80) Approach') !!}
                        {!! Form::select('atl_app', $user->uncertified_certified_a80, $user->atl_app, ['class' => 'form-control', $atl_app_disable]) !!}
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
        </events>
    </div>
    {!! Form::close() !!}
</div>
{{Html::script(asset('js/roster.js'))}}
@endsection
