@extends('layouts.dashboard')

@section('title')
    Send New Email
@endsection

@section('content')
@include('inc.header', ['title' => 'Send New Email'])

<div class="container">
    {{ html()->form()->route('sendEmail')->open() }}
        @csrf
        <div class="row mb-3">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="from">From</label>
                    {{ html()->text('from', 'info@notams.ztlartcc.org')->class(['form-control'])->attributes(['disabled']) }}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    {{ html()->text('name', null)->placeholder('Name (Required)')->class(['form-control']) }}
                </div>
            </div>
            <div class="col-sm-4">
                <label for="reply_to">Reply to Email</label>
                {{ html()->text('reply_to', null)->placeholder('ex. youremail@ztlartcc.org (Required)')->class(['form-control']) }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="to">To (Single Person)</label>
                    {{ html()->select('to', $controllers, null, ['placeholder' => 'Select Controller'])->class(['form-select']) }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="bulk">Bulk Email</label>
                    {{ html()->select('bulk', [
                        0 => 'All Controllers',
                        1 => 'Home Controllers',
                        6 => 'Home Observers',
                        7 => 'Home S1s',
                        8 => 'Home S2s',
                        9 => 'Home S3s',
                        10 => 'Home C1/C3s',
                        2 => 'Visiting Controllers',
                        3 => 'Mentors',
                        4 => 'Instructors',
                        5 => 'All Training Staff'
                    ], null)->placeholder('N/A')->class(['form-select']) }}
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="subject">Subject</label>
            {{ html()->text('subject', null)->placeholder('Subject (Required)')->class(['form-control']) }}
        </div>
        <div class="form-group mb-3">
            <label for="message">Message</label>
            {{ html()->textarea('message', null)->placeholder('Message (Required)')->class(['form-control', 'text-editor']) }}
        </div>
        <button class="btn btn-success" type="submit">Send</button>
    {{ html()->form()->close() }}
</div>

@endsection
