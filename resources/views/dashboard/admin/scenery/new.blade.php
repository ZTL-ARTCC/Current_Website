@extends('layouts.dashboard')

@section('title')
New Scenery
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>New Scenery</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => 'AdminDash@storeScenery']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('apt', 'Airport Name') !!}
                    {!! Form::text('apt', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('sim', 'Simulator/AFCAD') !!}
                    {!! Form::select('sim', [
                        0 => 'FSX/P3D',
                        1 => 'X-Plane',
                        2 => 'AFCAD'
                    ], null, ['class' => 'form-control', 'placeholder' => 'Choose Simulator (Required)']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('price', 'Price') !!}
                    {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'If it is free, put "0" (Required)']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('currency', 'Currency') !!}
                    {!! Form::text('currency', 'USD', ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('url', 'Link to Scenery') !!}
                    {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('dev', 'Developer') !!}
                    {!! Form::text('dev', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('image1', 'Image 1') !!}
            {!! Form::text('image1', null, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('image2', 'Image 2') !!}
            {!! Form::text('image2', null, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('image3', 'Image 3') !!}
            {!! Form::text('image3', null, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
    {!! Form::close() !!}
            <div class="col-sm-1">
                <a href="/dashboard/admin/scenery" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>
@endsection
