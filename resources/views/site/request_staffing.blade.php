@extends('layouts.master')

@section('title')
Staffing Request
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>ZTL ARTCC Staffing Request</h2>
        &nbsp;
    </div>
</span>
<br>

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
                    {!! Form::label('date', 'Date of Staffing:', ['class' => 'control-label']) !!}
                    {!! Form::date('date', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('time', 'Time of Staffing (in Zulu):', ['class' => 'control-label']) !!}
                    {!! Form::time('time', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('additional_information', 'Additional Information:', ['class' => 'control-label']) !!}
            {!! Form::textArea('additional_information', null, ['placeholder' => 'Please include all additional relevant information regarding the need for staffing.', 'class' => 'form-control']) !!}
        </div>
        <div class="g-recaptcha" data-sitekey="6LcC3XoUAAAAAG8ST6HXqS3_reIZRLcA09sDdodw"></div>
        <br>
        <button class="btn btn-success" type="submit">Send Request</button>
    {!! Form::close() !!}
</div>
@endsection
