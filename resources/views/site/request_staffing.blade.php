@extends('layouts.master')

@section('title')
Staffing Request
@endsection

@section('content')
@include('inc.header', ['title' => 'ZTL ARTCC Staffing Request', 'type' => 'external'])

<div class="container">
    {!! Form::open(['action' => 'FrontController@staffRequest']) !!}
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            {!! Form::label('name', 'Your Name:', ['class' => 'control-label']) !!}
                            {!! Form::text('name', null, ['placeholder' => 'Your Name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('email', 'Your Email:', ['class' => 'control-label']) !!}
                            {!! Form::email('email', null, ['placeholder' => 'Your Email', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('org', 'Organization (If Applicable):', ['class' => 'control-label']) !!}
                    {!! Form::text('org', null, ['placeholder' => 'Organization if applicable (Optional)', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('date', 'Date of Staffing', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {!! Form::text('date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker1']) !!}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('time', 'Time of Staffing (Zulu)', ['class' => 'form-label']) !!}
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-target-input="nearest">
                        {!! Form::text('time', null, ['placeholder' => '00:00', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker2']) !!}
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('additional_information', 'Additional Information:', ['class' => 'control-label']) !!}
            {!! Form::textArea('additional_information', null, ['placeholder' => 'Please include all additional relevant information regarding the need for staffing.', 'class' => 'form-control']) !!}
        </div>
        <body>
            <form action="?" method="POST">
				<div class="g-recaptcha" data-sitekey="6LcC3XoUAAAAAG8ST6HXqS3_reIZRLcA09sDdodw"></div>
                <br/>
                <input class="btn btn-success" type="submit" value="Send Request">
            </form>
        </body>
        <br>
    {!! Form::close() !!}
</div>
@endsection
