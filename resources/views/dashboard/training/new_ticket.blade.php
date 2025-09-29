@extends('layouts.dashboard')

@section('title')
New Training Ticket
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/trainingticket.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Submit New Training Ticket'])

<div class="container">
    {{ html()->form()->route('saveTicket')->attributes(['id'=>'newTrainingTicket'])->open() }}
        @csrf
        {{ html()->hidden('scheddy_id', null) }}
        <div class="row-mb-3">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="controller" class="form-label">Controller</label>
                    @php
                        $c = (is_null($c)) ? null : $c;
                    @endphp
                    {{ html()->select('controller', $controllers, $c)->placeholder('Select Controller')->class(['form-select']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="position" class="form-label">Session Category</label>
                    {{ html()->select('position', $positions, null)->placeholder('Select Training Session')->class(['form-select']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="session_id" class="form-label">Session ID</label>
                    {{ html()->select('session_id', $session_ids, null)->placeholder('Select Session ID')->class(['form-select']) }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="type" class="form-label">Progress</label>
                    {{ html()->select('type', $progress_types, null)->placeholder('Select Progress Type')->class(['form-select']) }}
                </div>
            </div>
        </div>
        <div class="row-mb-3">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker1']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                            <i class="fas fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
					@php 
						$currentDateET = new DateTime("now", new DateTimeZone('America/New_York') ); 
						$currentTimeET = $currentDateET->format('H:i');
					@endphp
                    <label for="start" class="form-label">Start Time ET (now {{ $currentTimeET }})</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('start', null)->placeholder('00:00')->class(['form-control datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker2']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="end" class="form-label">End Time ET</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker3" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('end', null)->placeholder('00:00')->class(['form-control datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker3']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="duration" class="form-label">Duration (hh:mm)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker4" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('duration', null)->placeholder('00:00')->class(['form-control datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker4']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row-mb-3">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="score" class="form-label">Score (Visible to Controller)</label>
                    <div id="stars" class="star-input input-group">
                        <span data-rating="1">&star;</span>
                        <span data-rating="2">&star;</span>
                        <span data-rating="3">&star;</span>
                        <span data-rating="4">&star;</span>
                        <span data-rating="5">&star;</span>
                        {{ html()->text('score', null)->attribute('hidden') }}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="movements" class="form-label">Movements (Optional)</label>
                    <div class="input-group">
                        {{ html()->text('movements', null)->class('form-control') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row-mb-3">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="comments" class="form-label">Comments (Visible to Controller and other Trainers)</label>
                    {{ html()->textarea('comments', null)->class(['form-control', 'text-editor']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="trainer_comments" class="form-label">Trainer Comments (Visible to Only Other Trainers)</label>
                    {{ html()->textarea('trainer_comments', null)->class(['form-control', 'text-editor']) }}
                </div>
            </div>
        </div>
        <label for="ots" class="form-label">Recommend for OTS?</label>
        {{ html()->checkbox('ots', false, '1') }}
        <br>
        <label for="monitor" class="form-label">Can be monitored</label>
		{{ html()->checkbox('monitor', false, '1') }}
		<br>
        <label for="cert" class="form-label">Certification or Solo Issued</label>
		{{ html()->checkbox('cert', false, 1) }}
		<br>
        <p id="autosaveIndicator" class="font-italic">Last autosaved at: Not yet saved</p>
        <button class="btn btn-primary" type="submit" name="action" value="draft">Save as Draft</button>
        <button class="btn btn-success" type="submit" name="action" value="new">Finalize Ticket</button>
        <a href="/dashboard/training/tickets" class="btn btn-danger">Cancel</a>
    {{ html()->form()->close() }}
</div>

@if (count($recent_sessions) > 0)
    <div class="modal fade" id="showSuggestions" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Would you like to prefill any of these sessions?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @foreach($recent_sessions as $session)
                <div class="card mb-2 session-suggestion-card" role="button" onclick='fillSession(@json($session))'>
                    <div class="card-body">
                        <p class="mb-0 font-weight-light small float-right">{{ $session["date"] }} {{ $session["start_time"] }}</p>
                        <p class="mb-0">{{ $session["student_name"] }} - {{ $session["lesson_name"] }}</p>
                      </div>
                </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
@endif

<script src="{{mix('js/trainingticket.js')}}"></script>
@endsection
