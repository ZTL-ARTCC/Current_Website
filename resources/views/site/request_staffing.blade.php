@extends('layouts.master')

@section('title')
Staffing Request
@endsection

@section('content')
@include('inc.header', ['title' => 'ZTL ARTCC Staffing Request', 'type' => 'external'])

<div class="container">
    {{ html()->form()->route('staffRequest') }}
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="name" class="control-label">Your Name:</label>
                            {{ html()->text('name', null)->placeholder('Your Name')->class(['form-control']) }}
                        </div>
                        <div class="col-sm-6">
                            <label for="email" class="control-label">Your Email:</label>
                            {{ html()->email('email', null)->placeholder('Your Email')->class(['form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="org" class="control-label">Organization (If Applicable):</label>
                    {{ html()->text('org', null)->placeholder('Organization if applicable (Optional)')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="date" class="form-label">Date of Staffing</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="time" class="form-label">Time of Staffing (Zulu)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {{ html()->text('time', null)->placeholder('00:00')->class(['form-control datetimepicker-input'])->attributes(['data-target' => '#datetimepicker2']) }}
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="additional_information" class="control-label">Additional Information:</label>
            {{ html()->textarea('additional_information', null)->placeholder('Please include all additional relevant information regarding the need for staffing.')->class(['form-control']) }}
        </div>
        <body>
            <form action="?" method="POST">
				<div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
                <br/>
                <input class="btn btn-success" type="submit" value="Send Request">
            </form>
        </body>
        <br>
    {{ html()->form()->close() }}
</div>
@endsection
