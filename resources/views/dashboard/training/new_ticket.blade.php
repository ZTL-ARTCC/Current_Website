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
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller', ['class' => 'form-label']) !!}
                    @if($c != null)
                        {!! Form::select('controller', $controllers, $c, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::select('controller', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                    @endif
                </div>
            </div>
            <div class="col-sm-3">
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
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('sessionId', 'Session ID', ['class' => 'form-label']) !!}
                    {!! Form::select('sessionId', [
                        100 => 'DEL0 - S1T0-O',
                        101 => 'DEL1 - S1T1',
                        102 => 'DEL2 - S1P1',
                        103 => 'DEL3 - S1T2',
                        104 => 'DEL4 - S1P2-S',
                        105 => 'GND1 - S1T3-S',
                        106 => 'TWR1 - S2T1',
                        107 => 'TWR2 - S2T2',
                        108 => 'TWR3 - S2P1-S',
                        109 => 'TWR4 - S2T3-S',
                        110 => 'TWR5 - S2P2-S',
                        111 => 'TWR6 - S2T4-S',
                        112 => 'TWR7 - S2T5-S',
                        113 => 'TWR8 - S2T6',
                        114 => 'TWR9 - S2P3-O',
                        115 => 'TWR10 - S2M1-S',
                        116 => 'APP1 - S3T1-S',
                        117 => 'APP2 - S3T2-S',
                        118 => 'APP3 - S3P1-S',
                        119 => 'APP4 - S3T3-S',
                        120 => 'APP5 - S3T4-S',
                        121 => 'APP6 - S3T5-S',
                        122 => 'APP7 - S3M1-S',
                        123 => 'APP8 - S3T6',
                        124 => 'APP9 - S3P2',
                        125 => 'APP10 - S3P3-O',
                        126 => 'APP11 - S3P4-O',
                        127 => 'APP12 - S3M2-S',
                        128 => 'CTR0 - C1T0-O',
                        129 => 'CTR1 - C1T1-S',
                        130 => 'CTR2 - C1T2-S',
                        131 => 'CTR3 - C1T3-S',
                        132 => 'CTR4 - C1P1-S',
                        133 => 'CTR5 - C1P2-S',
                        134 => 'CTR6 - C1M1-S',
                        135 => 'CTR7 - C1M2-S',
                        136 => 'ZTL1 - M1M1-S',
                        137 => 'ATL1 - M2T1',
                        138 => 'ATL2 - M2M1-O',
                        139 => 'ATL3 - M2T2',
                        140 => 'ATL4 - M2P1',
                        141 => 'ATL5 - M2M2-O',
                        142 => 'ATL6 - M2T3',
                        143 => 'ATL7 - M2T4',
                        144 => 'ATL8 - M2M3-S',
                        145 => 'A801 - M3P1-S',
                        146 => 'A802 - M3M1-O',
                        147 => 'A803 - M3P2',
                        148 => 'A804 - M3M2-O',
                        149 => 'A805 - M3T1-S',
                        150 => 'A806 - M3P3',
                        151 => 'A807 - M3P4',
                        152 => 'A808 - M3M3-O',
                        153 => 'A809 - M3T3-O',
                        154 => 'A8010 - M3T4-S',
                        155 => 'A8011 - M3P5',
                        156 => 'A8012 - M3M4-S',						
                    ], null, ['placeholder' => 'Select Session ID', 'class' => 'form-control'], array('disabled')) !!}
                </div>
            </div>
            <div class="col-sm-3">
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
                    {!! Form::label('start', 'Start Time in Eastern', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('start', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('end', 'End Time in Eastern', ['class' => 'form-label']) !!}
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
