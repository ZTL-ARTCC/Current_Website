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
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller', ['class' => 'form-label']) !!}
                    {!! Form::select('controller', $controllers, $ticket->controller_id, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('position', 'Lesson', ['class' => 'form-label']) !!}
                    {!! Form::select('position', [
                                               7 => 'S1T1-DEL-1 (Theory)',
                        8 => 'S1P1-DEL-2',
                        9 => 'S1P2-DEL-3',
                        10 => 'S1M1-DEL-4 (Live Network Monitoring – CLT)',
                        11 => 'S1T2-DEL-5 (Theory, ATL)',
                        12 => 'S1P3-DEL-6',
                        13 => 'S1M2-DEL-7 (Live Network Monitoring – ATL)',
                        14 => 'S1T3-GND-1 (Theory)',
                        15 => 'S1P4-GND-2',
                        16 => 'S1P5-GND-3',
                        17 => 'S1M3-GND-4 (Live Network Monitoring – CLT)',
                        18 => 'S1T4-GND-5 (Theory, ATL)',
                        19 => 'S1P6-GND-6',
                        20 => 'S1P7-GND-7',
                        21 => 'S1M4-GND-8 (Live Network Monitoring – ATL)',
                        22 => 'S2T1-TWR-1 (Theory)',
                        23 => 'S2P1-TWR-2',
                        24 => 'S2P2-TWR-3',
                        25 => 'S2P3-TWR-4',
                        26 => 'S2M1-TWR-5 (Live Network Monitoring – CLT)',
                        27 => 'S2T2-TWR-6 (Theory, ATL)',
                        28 => 'S2P4-TWR-7',
                        29 => 'S2P5-TWR-8',
                        30 => 'S2M2-TWR-9 (Live Network Monitoring – ATL)',
                        31 => 'S3T1-APP-1 (Theory)',
                        32 => 'S3T2-APP-2 (Theory)',
                        33 => 'S3P1-APP-3',
                        34 => 'S3P2-APP-4',
                        35 => 'S3M1-APP-5 (Live Network Monitoring - BHM/CLT)',
                        36 => 'S3T3-APP-6 (Theory)',
                        37 => 'S3P3-APP-7',
                        38 => 'S3P4-APP-8',
                        39 => 'S3P5-APP-9',
                        40 => 'S3P6-APP-10',
                        41 => 'S3M2-APP-11 (Live Network Monitoring – ATL)',
                        42 => 'C1T1-CTR-1 (Theory)',
                        43 => 'C1P1-CTR-2',
                        45 => 'C1P3-CTR-3',
                        46 => 'C1M2-CTR-4',
                        47 => 'C1M3-CTR-5 (Live Network Monitoring)',
                        48 => 'C1M4-CTR-6',
                        49 => 'S1 Visiting Major Checkout',
                        50 => 'S2 Visiting Major Checkout',
                        51 => 'S3 Visiting Major Checkout',
                        52 => 'C1 Visiting Major Checkout',
                        53 => 'Enroute OTS',
                        54 => 'Approach OTS',
                        55 => 'Local OTS',
                    ], $ticket->position, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
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
                    {!! Form::label('start', 'Start Time in Zulu', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('start', $ticket->start_time, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('end', 'End Time in Zulu', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        {!! Form::text('end', $ticket->end_time, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker3']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('duration', 'Duration (HH:mm)', ['class' => 'form-label']) !!}
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
