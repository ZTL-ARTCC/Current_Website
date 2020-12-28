@extends('layouts.dashboard')

@section('title')
New Training Ticket
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Submit New Training Ticket</h2>
    &nbsp;
</div>
<br>

<div class="container">
    {!! Form::open(['action' => 'TrainingDash@saveNewTicket']) !!}
        @csrf
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller', ['class' => 'form-label']) !!}
                    @if($c != null)
                        {!! Form::select('controller', $controllers, $c, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::select('controller', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('position', 'Training Session', ['class' => 'form-label']) !!}
                    {!! Form::select('position', [
                                                 100 => 'ZTL On-Boarding',
                        101 => 'Class D/C Clearance Delivery',
                        102 => 'Class B Clearance Delivery',
                        103 => 'ATL Clearance Delivery Theory',
                        104 => 'ATL Clearance',
                        105 => 'Class D/C Ground',
                        106 => 'Class B Ground',
                        107 => 'ATL Ground Theory',
                        108 => 'ATL Ground',
                        109 => 'Class D Tower',
                        110 => 'Class C Tower',
                        111 => 'Class B Tower',
                        112 => 'ATL Tower Theory',
                        113 => 'ATL Tower',
                        114 => 'Minor Approach Introduction',
                        115 => 'Minor Approach',
                        116 => 'CLT Approach',
                        117 => 'A80 Departure/Satellite Radar',
                        118 => 'A80 Terminal Arrival Radar',
                        119 => 'A80 Arrival Radar',
                        120 => 'Atlanta Center Introduction',
                        121 => 'Atlanta Center',
                        122 => 'Recurrent Training',
                    ], null, ['placeholder' => 'Select Training Session', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    @if(Auth::user()->hasRole('ins'))
                        {!! Form::label('type', 'Progress', ['class' => 'form-label']) !!}
                        {!! Form::select('type', [
                            10 => 'No Show',
                            12 => 'Completed',
                            13 => 'Incompleted',
                        ], null, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::label('type', 'Progress', ['class' => 'form-label']) !!}
                        {!! Form::select('type', [
                            10 => 'No Show',
                            12 => 'Completed',
                            13 => 'Incompleted',


                        ], null, ['placeholder' => 'Select Progress Type', 'class' => 'form-control']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('date', 'Date', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('start', 'Start Time in Zulu', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('start', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('end', 'End Time in Zulu', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        {!! Form::text('end', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker3']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('duration', 'Duration (HH:mm)', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                        {!! Form::text('duration', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker4']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('comments', 'Comments (Visible to Controller and other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('comments', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('trainer_comments', 'Trainer Comments (Visible to Only Other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('trainer_comments', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        {!! Form::label('ots', 'Recommend for OTS?', ['class' => 'form-label']) !!}
        {!! Form::checkBox('ots', '1') !!}
        <br>
    {!! Form::label('monitor', 'Can be monitored', ['class' => 'form-label']) !!}
    {!! Form::checkBox('monitor', '1') !!}
    <br>
        <button class="btn btn-success" action="submit">Submit Ticket</button>
        <a href="/dashboard/training/tickets" class="btn btn-danger">Cancel</a>
    {!! Form::close() !!}
</div>
<script type="text/javascript">
$(function () {
    $('#datetimepicker1').datetimepicker({
        format: 'L'
    });
});

$(function () {
    $('#datetimepicker2').datetimepicker({
        format: 'HH:mm'
    });
});

$(function () {
    $('#datetimepicker3').datetimepicker({
        format: 'HH:mm'
    });
});

$(function () {
    $('#datetimepicker4').datetimepicker({
        format: 'HH:mm'
    });
});
</script>
<script>
    $(document).ready(function ($) {
        $('#timepicker').datetimepicker({
            format: 'hh:mm a'
        });
    });
</script>
@endsection
