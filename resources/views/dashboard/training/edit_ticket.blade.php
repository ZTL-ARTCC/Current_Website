@extends('layouts.dashboard')

@section('title')
Edit Training Ticket
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Edit Training Ticket</h2>
    &nbsp;
</div>
<br>

<div class="container">
    {!! Form::open(['action' => ['TrainingDash@saveTicket', $ticket->id]]) !!}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller', ['class' => 'form-label']) !!}
                    {!! Form::select('controller', [  $ticket->controller_id => $ticket->Controller_name], NULL ,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('position', 'Session Category', ['class' => 'form-label']) !!}
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
						123 => 'BHM Approach',
                        116 => 'CLT Approach',
                        117 => 'A80 Departure/Satellite Radar',
                        118 => 'A80 Terminal Arrival Radar',
                        119 => 'A80 Arrival Radar',
                        120 => 'Atlanta Center Introduction',
                        121 => 'Atlanta Center',
                        122 => 'Recurrent Training',
                    ], $ticket->position, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('session_id', 'Session ID', ['class' => 'form-label']) !!}
                    {!! Form::select('session_id', [
                        200 => 'DEL0 - S1T0-O',
                        201 => 'DEL1 - S1T1',
                        202 => 'DEL2 - S1P1',
                        203 => 'DEL3 - S1T2',
                        204 => 'DEL4 - S1P2-S',
                        205 => 'GND1 - S1T3-S',
                        206 => 'TWR1 - S2T1',
                        207 => 'TWR2 - S2T2',
                        208 => 'TWR3 - S2P1-S',
                        209 => 'TWR4 - S2T3-S',
                        210 => 'TWR5 - S2P2-S',
                        211 => 'TWR6 - S2T4-S',
                        212 => 'TWR7 - S2T5-S',
                        213 => 'TWR8 - S2T6',
                        214 => 'TWR9 - S2P3-O',
                        215 => 'TWR10 - S2M1-S',
                        216 => 'APP1 - S3T1-S',
                        217 => 'APP2 - S3T2-S',
                        218 => 'APP3 - S3P1-S',
                        219 => 'APP4 - S3T3-S',
                        220 => 'APP5 - S3T4-S',
                        221 => 'APP6 - S3T5-S',
                        222 => 'APP7 - S3M1-S',
                        223 => 'APP8 - S3T6',
                        224 => 'APP9 - S3P2',
                        225 => 'APP10 - S3P3-O',
                        226 => 'APP11 - S3P4-O',
                        227 => 'APP12 - S3M2-S',
                        228 => 'CTR0 - C1T0-O',
                        229 => 'CTR1 - C1T1-S',
                        230 => 'CTR2 - C1T2-S',
                        231 => 'CTR3 - C1T3-S',
                        232 => 'CTR4 - C1P1-S',
                        233 => 'CTR5 - C1P2-S',
                        234 => 'CTR6 - C1M1-S',
                        235 => 'CTR7 - C1M2-S',
                        236 => 'ZTL1 - M1M1-S',
                        237 => 'ATL1 - M2T1',
                        238 => 'ATL2 - M2M1-O',
                        239 => 'ATL3 - M2T2',
                        240 => 'ATL4 - M2P1',
                        241 => 'ATL5 - M2M2-O',
                        242 => 'ATL6 - M2T3',
                        243 => 'ATL7 - M2T4',
                        244 => 'ATL8 - M2M3-S',
                        245 => 'A801 - M3P1-S',
                        246 => 'A802 - M3M1-O',
                        247 => 'A803 - M3P2',
                        248 => 'A804 - M3M2-O',
                        249 => 'A805 - M3T1-S',
                        250 => 'A806 - M3P3',
                        251 => 'A807 - M3P4',
                        252 => 'A808 - M3M3-O',
                        253 => 'A809 - M3T3-O',
                        254 => 'A8010 - M3T4-S',
                        255 => 'A8011 - M3P5',
                        256 => 'A8012 - M3M4-S',
						257 => 'Unlisted/other',					
                    ], $ticket->session_id, ['placeholder' => 'Select Session ID', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    @if(Auth::user()->hasRole('ins') || Auth::user()->can('snrStaff'))
                        {!! Form::label('type', 'Session Type', ['class' => 'form-label']) !!}
                        {!! Form::select('type', [
                            10 => 'No Show',
                            12 => 'Completed',
                            13 => 'Incompleted',
                        ], $ticket->type, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::label('type', 'Session Type', ['class' => 'form-label']) !!}
                        {!! Form::select('type', [
                            10 => 'No Show',
                            12 => 'Completed',
                            13 => 'Incompleted',
                        ], $ticket->type, ['placeholder' => 'Select Session Type', 'class' => 'form-control']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('date', 'Date', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', $ticket->date, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
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
                        {!! Form::text('start', $ticket->start_time, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('end', 'End Time in Eastern', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        {!! Form::text('end', $ticket->end_time, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker3']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('duration', 'Duration (hh:mm)', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                        {!! Form::text('duration', $ticket->duration, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker4']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('comments', 'Comments (Visible to Controller and other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('comments', $ticket->comments, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('trainer_comments', 'Trainer Comments (Visible to Only Other Trainers)', ['class' => 'form-label']) !!}
                    {!! Form::textArea('trainer_comments', $ticket->ins_comments, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br>
        <button class="btn btn-success" action="submit">Save Ticket</button>
        <a href="/dashboard/training/tickets/view/{{ $ticket->id }}" class="btn btn-danger">Cancel</a>
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
@endsection
