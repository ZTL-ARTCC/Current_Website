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
                    {!! Form::label('position', 'Position', ['class' => 'form-label']) !!}
                    {!! Form::select('position', [
                        0 => 'Minor Delivery/Ground',
                        1 => 'Minor Local',
                        2 => 'Major Delivery/Ground',
                        3 => 'Major Local',
                        4 => 'Minor Approach',
                        5 => 'Major Approach',
                        6 => 'Center'
                    ], $ticket->position, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    @if(Auth::user()->hasRole('ins') || Auth::user()->can('snrStaff'))
                        {!! Form::label('type', 'Session Type', ['class' => 'form-label']) !!}
                        {!! Form::select('type', [
                            0 => 'Classroom Training',
                            1 => 'Sweatbox Training',
                            2 => 'Live Training',
                            3 => 'Live Monitoring',
                            4 => 'Sweatbox OTS (Pass)',
                            5 => 'Live OTS (Pass)',
                            6 => 'Sweatbox OTS (Fail)',
                            7 => 'Live OTS (Fail)'
                        ], $ticket->type, ['placeholder' => 'Select Position', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::label('type', 'Session Type', ['class' => 'form-label']) !!}
                        {!! Form::select('type', [
                            0 => 'Classroom Training',
                            1 => 'Sweatbox Training',
                            2 => 'Live Training',
                            3 => 'Live Monitoring'
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
