@extends('layouts.dashboard')

@section('title')
New Calendar Event/News
@endsection

@section('content')
@include('inc.header', ['title' => 'New Calendar Event/News'])

<div class="container">
    {{ html()->form()->route('storeCalendarEvent')->open() }}
        @csrf
        <div class="form-group mb-3">
        <label for="title">Title</label>
        {{ html()->text('title', null)->class(['form-control'])->placeholder('Required') }}
        </div>
        <div class="form-group mb-3">
            <div class="row">
                <div class="col-sm-6">
                    <label for="date">Date</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker1']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                            <i class="fas fa-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="time">Time</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('time', null)->placeholder('HH:MM (Optional)')->class(['form-control datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker2']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker2" data-td-toggle="datetimepicker">
                            <i class="fas fa-clock"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="body">Additional Information</label>
            {{ html()->textarea('body', null)->class(['form-control', 'text-editor'])->placeholder('Required') }}
        </div>
        <div class="form-group mb-3">
            <label for="type">Type of Post</label>
            {{ html()->select('type', [
                1 => 'Calendar Event',
                2 => 'News'
            ], null)->class(['form-select']) }}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
    {{ html()->form()->close() }}
            <div class="col-sm-1">
                <a href="/dashboard/admin/calendar" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>

@endsection
