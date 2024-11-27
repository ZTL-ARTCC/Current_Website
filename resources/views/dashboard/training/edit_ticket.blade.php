@extends('layouts.dashboard')

@section('title')
Edit Training Ticket
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/trainingticket.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Edit Training Ticket'])

<div class="container">
    @if($ticket->draft)
        <span id="draft" class="badge badge-warning mb-3">DRAFT</span>
    @endif
    {{ html()->form()->route('saveTicket', [$ticket->id])->attributes(['id'=>'editTrainingTicket'])->open() }}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="controller" class="form-label">Controller</label>
                    {{ html()->select('controller', [$ticket->controller_id => $ticket->Controller_name], null)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="position" class="form-label">Session Category</label>
                    {{ html()->select('position', $positions, $ticket->position)->placeholder('Select Position')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="session_id" class="form-label">Session ID</label>
                    {{ html()->select('session_id', $session_ids, $ticket->session_id)->placeholder('Select Session ID')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="type" class="form-label">Progress</label>
                    {{ html()->select('type', $progress_types, $ticket->type)->placeholder('Select Session Type')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', $ticket->date)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    @if($ticket->draft)
                        @php 
						    $currentDateET = new DateTime("now", new DateTimeZone('America/New_York') ); 
						    $currentTimeET = $currentDateET->format('H:i');
					    @endphp
                        <label for="start" class="form-label">Start Time ET (now {{ $currentTimeET }})</label>
                    @else
                        <label for="start" class="form-label">Start Time ET</label>
                    @endif
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('start', $ticket->start_time)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker2']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="end" class="form-label">End Time ET</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-target-input="nearest">
                        {{ html()->text('end', $ticket->end_time)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker3']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="duration" class="form-label">Duration (hh:mm)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-target-input="nearest">
                        {{ html()->text('duration', $ticket->duration)->placeholder('00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker4']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="score" class="form-label">Score (Visible to Controller)</label>
                    <div id="stars" class="star-input input-group">
                        <span data-rating="1">&star;</span>
                        <span data-rating="2">&star;</span>
                        <span data-rating="3">&star;</span>
                        <span data-rating="4">&star;</span>
                        <span data-rating="5">&star;</span>
                        {{ html()->text('score', $ticket->score)->attribute('hidden') }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="movements" class="form-label">Movements (Optional)</label>
                    <div class="input-group">
                        {{ html()->text('movements', $ticket->movements)->class('form-control') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="comments" class="form-label">Comments (Visible to Controller and other Trainers)</label>
                    {{ html()->textarea('comments', null)->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="trainer_comments" class="form-label">Trainer Comments (Visible to Only Other Trainers)</label>
                    {{ html()->textarea('trainer_comments', null)->class(['form-control']) }}
                </div>
            </div>
        </div>
        @if($ticket->draft)
            <label for="ots" class="form-label">Recommend for OTS?</label>
            @if($ticket->ots == 1)
                {{ html()->checkbox('ots', false, '1') }}
            @else
                {{ html()->checkbox('ots', false, '1') }}
            @endif
            <br>
        @endif
        <label for="monitor" class="form-label">Can be monitored</label>
        @if($ticket->monitor == 1)
			{{ html()->checkbox('monitor', true, 1) }}
        @else
			{{ html()->checkbox('monitor', false, 1) }}
        @endif	
        <br>
        <label for="cert" class="form-label">Certification or Solo Issued</label>
        @if($ticket->cert == 1)
			{{ html()->checkbox('cert', true, 1) }}
        @else
			{{ html()->checkbox('cert', false, 1) }}
        @endif		
		<br>
        <br>
        @if ($ticket->draft)
            <p id="autosaveIndicator" class="font-italic">Last autosaved at: Not yet saved</p>
            <button class="btn btn-primary" type="submit" name="action" value="draft">Save as Draft</button>
            <button class="btn btn-success" type="submit" name="action" value="new">Finalize Ticket</button>
            @if(Auth::id() == $ticket->trainer_id || Auth::user()->isAbleTo('snrStaff'))
                <a class="btn btn-danger" href="/dashboard/training/tickets/delete/{{ $ticket->id }}">Delete Ticket</a>
            @endif
        @else
            <button class="btn btn-success" type="submit" name="action" value="save">Update Ticket</button>
        @endif
        <a href="/dashboard/training/tickets/view/{{ $ticket->id }}" class="btn btn-danger">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{mix('js/trainingticket.js')}}"></script>
@endsection
