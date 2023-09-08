@extends('layouts.dashboard')

@section('title')
Update Controller
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Update {{ $user->full_name }} ({{ $user->id }})</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => ['AdminDash@updateController', $user->id]]) !!}
        @csrf
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
                    {!! Form::label('del', 'Delivery Certification') !!}
                    @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train'))
                        {!! Form::select('del', [
                            0 => 'None',
                            1 => 'Minor Certified',
                            88 => 'Minor Monitoring',
                            89 => 'Major Monitoring',
                            2 => 'Major Certified'
                        ], $user->del, ['class' => 'form-control']) !!}
                    @else
                        {!! Form::select('del', [
                            0 => 'None',
                            1 => 'Minor Certified',
                            88 => 'Minor Monitoring',
                            89 => 'Major Monitoring',
                            2 => 'Major Certified'
                        ], $user->del, ['class' => 'form-control', 'disabled']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('lname', 'Last Name') !!}
                    {!! Form::text('lname', $user->lname, ['class' => 'form-control', 'disabled']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('gnd', 'Ground Certification') !!}
                    @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train'))
                        {!! Form::select('gnd', [
                            0 => 'None',
                            1 => 'Minor Certified',
                            2 => 'Major Certified'
                        ], $user->gnd, ['class' => 'form-control']) !!}
                    @else
                        {!! Form::select('gnd', [
                            0 => 'None',
                            1 => 'Minor Certified',
                            2 => 'Major Certified'
                        ], $user->gnd, ['class' => 'form-control', 'disabled']) !!}
                    @endif
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
                    {!! Form::label('twr', 'Tower Certification') !!}
                    @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train'))
                        {!! Form::select('twr', [
                            0 => 'None',
                            99 => 'Solo Certification',
                            1 => 'Minor Certified',
                            88 => 'Minor Montoring',
                            89 => 'Major Montoring',
                            2 => 'Major Certified'
                        ], $user->twr, ['class' => 'form-control']) !!}
                    @else
                    {!! Form::select('twr', [
                            0 => 'None',
                            99 => 'Solo Certification',
                            1 => 'Minor Certified',
                            88 => 'Minor Montoring',
                            89 => 'Major Montoring',
                            2 => 'Major Certified'
                        ], $user->twr, ['class' => 'form-control', 'disabled']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('initials', 'Initials') !!}
                    @if(Auth::user()->isAbleTo('roster'))
                        {!! Form::text('initials', $user->initials, ['class' => 'form-control']) !!}
                    @else
                        {!! Form::text('initials', $user->initials, ['class' => 'form-control', 'disabled']) !!}
                    @endif
                </div>
                <div class="col-sm-6">
                    {!! Form::label('app', 'Approach Certification') !!}
                    @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train'))
                        {!! Form::select('app', [
                            0 => 'None',
                            99 => 'Solo Certification',
                            1 => 'Minor Certified',
                            88 => 'Minor Montoring',
                        89 => 'Major Montoring',
                        90 => 'A80 SAT Certified',
                        91 => 'A80 DR Certified',
                        92 => 'A80 TAR Certified',
                            2 => 'Major Certified'
                        ], $user->app, ['class' => 'form-control']) !!}
                    @else
                        {!! Form::select('app', [
                            0 => 'None',
                            99 => 'Solo Certification',
                            1 => 'Minor Certified',
                            88 => 'Minor Montoring',
                            89 => 'Major Montoring',
                            90 => 'A80 SAT Certified',
                            91 => 'A80 DR Certified',
                            92 => 'A80 TAR Certified',
                            2 => 'Major Certified'
                        ], $user->app, ['class' => 'form-control', 'disabled']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    @if(Auth::user()->isAbleTo('roster'))
                        @if($user->visitor == 1)
                            {!! Form::label('visitor_from', 'Visitor From') !!}
                            {!! Form::text('visitor_from', $user->visitor_from, ['class' => 'form-control']) !!}
                            {!! Form::hidden('status', $user->status) !!}
                        @else
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', [
                                1 => 'Active',
                                0 => 'LOA'
                            ], $user->status, ['class' => 'form-control']) !!}
                        @endif
                    @else
                    @if($user->visitor == 1)
                            {!! Form::label('visitor_from', 'Visitor From') !!}
                            {!! Form::text('visitor_from', $user->visitor_from, ['class' => 'form-control', 'disabled']) !!}
                    @else
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', [
                            1 => 'Active',
                            0 => 'LOA'
                        ], $user->status, ['class' => 'form-control', 'disabled']) !!}
                    @endif
                @endif
                </div>
                <div class="col-sm-6">
                    {!! Form::label('ctr', 'Center Certification') !!}
                    @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train'))
                        {!! Form::select('ctr', [
                            0 => 'None',
                            99 => 'Solo Certification',
                            1 => 'Certified'
                        ], $user->ctr, ['class' => 'form-control']) !!}
                    @else
                        {!! Form::select('ctr', [
                            0 => 'None',
                            99 => 'Solo Certification',
                            1 => 'Certified'
                        ], $user->ctr, ['class' => 'form-control', 'disabled']) !!}
                    @endif
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="row">
                @if(Auth::user()->isAbleTo('roster'))
                    <div class="col-sm-6">
                        {!! Form::label('staff', 'Staff Position') !!}
                        {!! Form::select('staff', [
                            0 => 'NONE',
                            1 => 'ATM',
                            2 => 'DATM',
                            3 => 'TA',
                            4 => 'ATA',
                            5 => 'WM',
                            6 => 'AWM',
                            7 => 'FE',
                            8 => 'AFE',
                            9 => 'EC',
                        ], $user->staff_position, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('events_staff', 'Events Staff') !!}
                        {!! Form::select('events_staff', [
                            0 => 'NONE',
                            1 => 'AEC',
                            2 => 'AEC (Ghost)',
                            3 => 'Events Team'
                            ], $user->events_position, ['class' => 'form-control']) !!}
                    </div>
                @elseif(Auth::user()->isAbleTo('events'))
                    <div class="col-sm-6">
                        {!! Form::label('staff', 'Staff Position') !!}
                        {!! Form::select('staff', [
                            0 => 'NONE',
                            1 => 'ATM',
                            2 => 'DATM',
                            3 => 'TA',
                            4 => 'ATA',
                            5 => 'WM',
                            6 => 'AWM',
                            7 => 'FE',
                            8 => 'AFE',
                            9 => 'EC',
                        ], $user->staff_position, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('events_staff', 'Events Staff') !!}
                        {!! Form::select('events_staff', [
                            0 => 'NONE',
                            1 => 'AEC',
                            2 => 'AEC (Ghost)',
                            3 => 'Events Team'
                            ], $user->events_position, ['class' => 'form-control']) !!}
                    </div>
                @else
                    <div class="col-sm-6">
                        {!! Form::label('staff', 'Staff Position') !!}
                        {!! Form::select('staff', [
                            0 => 'NONE',
                            1 => 'ATM',
                            2 => 'DATM',
                            3 => 'TA',
                            4 => 'ATA',
                            5 => 'WM',
                            6 => 'AWM',
                            7 => 'FE',
                            8 => 'AFE',
                            9 => 'EC',
                        ], $user->staff_position, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('events_staff', 'Events Staff') !!}
                        {!! Form::select('events_staff', [
                            0 => 'NONE',
                            1 => 'AEC',
                            2 => 'AEC (Ghost)',
                            3 => 'Events Team'
                            ], $user->events_position, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                @endif
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="row">
                @if(Auth::user()->isAbleTo('roster'))
                    <div class="col-sm-6">
                        {!! Form::label('training', 'Training Position') !!}
                        {!! Form::select('training', [
                            0 => 'NONE',
                            1 => 'MTR',
                            2 => 'INS'
                        ], $user->train_position, ['class' => 'form-control']) !!}
                    </div>
                    @if($user->hasRole('mtr') || $user->hasRole('ins'))
                        <div class="col-sm-6">
                            {!! Form::label('max_minor_del', 'Train Minor DEL') !!}
                            {!! Form::checkbox('max_minor_del', 1, true) !!}
                           
                            {!! Form::label('max_minor_gnd', 'Train Minor GND') !!}
                            {!! Form::checkbox('max_minor_gnd', 1, true) !!}

                            {!! Form::label('max_minor_twr', 'Train Minor TWR') !!}
                            {!! Form::checkbox('max_minor_twr', 1, true) !!}

                            {!! Form::label('max_minor_app', 'Train Minor APP') !!}
                            {!! Form::checkbox('max_minor_app', 1, true) !!}
                        </div>
                    @endif
                @else
                    <div class="col-sm-6">
                        {!! Form::label('training', 'Training Position') !!}
                        {!! Form::select('training', [
                            0 => 'NONE',
                            1 => 'MTR',
                            2 => 'INS'
                        ], $user->train_position, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    @if($user->hasRole('mtr') || $user->hasRole('ins'))
                        <div class="col-sm-6">
                            {!! Form::label('max', 'Training Level') !!}
                            {!! Form::select('max', [
                                null => 'Not Able to Train',
                                1 => 'Minor/ DEL & GMD',
                                2 => 'Major/ DEL & GMD',
                                3 => 'Minor Local',
                                4 => 'Major Local',
                                5 => 'Minor Approach',
                                6 => 'Major Approach',
                                7 => 'Center'
                            ], $user->max, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @if(Auth::user()->isAbleTo('roster') || Auth::user()->isAbleTo('train'))
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
						{!! Form::label('twr_solo_fields', 'Class D Tower Solo Certifications') !!}
						{!! Form::text('twr_solo_fields', $user->twr_solo_fields, ['class' => 'form-control','maxlength' => 255]) !!}
                    </div>
                    <div class="col-sm-6">
                    {!! Form::label('twr_solo_expires', 'Tower Solo Expiration Date', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('twr_solo_expires', $user->twr_solo_expires, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>		
        <hr>
        @endif
        @if(Auth::user()->isAbleTo('roster'))
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-10">
                        @if($user->canTrain == 1)
                            {!! Form::label('canTrain', 'Allow Training?') !!}
                            {!! Form::checkbox('canTrain', 1, true) !!}
                        @else
                            {!! Form::label('canTrain', 'Allow Training?') !!}
                            {!! Form::checkbox('canTrain', 1) !!}
                        @endif
                    </div>
                    <div class="col-sm-10">
                        @if($user->visitor == 1)
                            {!! Form::label('visitor', 'Visitor?') !!}
                            {!! Form::checkbox('visitor', 1, true) !!}
                            <a href="/dashboard/admin/roster/visit/remove/{{ $user->id }}">(Remove from Roster)</a>
                        @else
                            {!! Form::label('visitor', 'Visitor?') !!}
                            {!! Form::checkbox('visitor', 1) !!}
                        @endif
                    </div>
                    <div class="col-sm-10">
                        @if($user->canEvents == 1)
                            {!! Form::label('canEvents', 'Allow Signing up for Events?') !!}
                            {!! Form::checkbox('canEvents', 1, true) !!}
                        @else
                            {!! Form::label('canEvents', 'Allow Signing up for Events?') !!}
                            {!! Form::checkbox('canEvents', 1) !!}
                        @endif
                    </div>
                    @if($user->visitor != 1)
                        <div class="col-sm-10">
                            @if($user->api_exempt == 1)
                                {!! Form::label('api_exempt', 'Exempt from VATUSA API Roster Update?') !!}
                                {!! Form::checkbox('api_exempt', 1, true) !!}
                            @else
                                {!! Form::label('api_exempt', 'Exempt from VATUSA API Roster Update?') !!}
                                {!! Form::checkbox('api_exempt', 1) !!}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-10">
                        @if($user->canTrain == 1)
                            {!! Form::label('canTrain', 'Allow Training?') !!}
                            {!! Form::checkbox('canTrain', 1, true, ['disabled']) !!}
                        @else
                            {!! Form::label('canTrain', 'Allow Training?') !!}
                            {!! Form::checkbox('canTrain', 1, null, ['disabled']) !!}
                        @endif
                    </div>
                    <div class="col-sm-10">
                        @if($user->visitor == 1)
                            {!! Form::label('visitor', 'Visitor?') !!}
                            {!! Form::checkbox('visitor', 1, true, ['disabled']) !!}
                        @else
                            {!! Form::label('visitor', 'Visitor?') !!}
                            {!! Form::checkbox('visitor', 1, null, ['disabled']) !!}
                        @endif
                    </div>
                    <div class="col-sm-10">
                        @if($user->canEvents == 1)
                            {!! Form::label('canEvents', 'Allow Signing up for Events?') !!}
                            {!! Form::checkbox('canEvents', 1, true, ['disabled']) !!}
                        @else
                            {!! Form::label('canEvents', 'Allow Signing up for Events?') !!}
                            {!! Form::checkbox('canEvents', 1, null, ['disabled']) !!}
                        @endif
                    </div>
                    @if($user->visitor != 1)
                        <div class="col-sm-10">
                            @if($user->api_exempt == 1)
                                {!! Form::label('api_exempt', 'Exempt from VATUSA API Roster Update?') !!}
                                {!! Form::checkbox('api_exempt', 1, true, ['disabled']) !!}
                            @else
                                {!! Form::label('api_exempt', 'Exempt from VATUSA API Roster Update?') !!}
                                {!! Form::checkbox('api_exempt', 1, null, ['disabled']) !!}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <br>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
    {!! Form::close() !!}
            <div class="col-sm-1">
                <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>
<script type="text/javascript">
$(function () {
    $('#datetimepicker1').datetimepicker({
        format: 'L'
    });
});
</script>
@endsection
