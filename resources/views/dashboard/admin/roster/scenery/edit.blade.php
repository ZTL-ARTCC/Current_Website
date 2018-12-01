@extends('layouts.dashboard')

@section('title')
Edit Scenery
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Edit Scenery</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => ['AdminDash@saveScenery', $scenery->id]]) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('apt', 'Airport Name') !!}
                    {!! Form::text('apt', $scenery->airport, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('sim', 'Simulator/AFCAD') !!}
                    {!! Form::select('sim', [
                        0 => 'FSX/P3D',
                        1 => 'X-Plane',
                        2 => 'AFCAD'
                    ], $scenery->sim, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('url', 'Link to Scenery') !!}
                    {!! Form::text('url', $scenery->link, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('dev', 'Developer') !!}
                    {!! Form::text('dev', $scenery->developer, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('image1', 'Image 1') !!}
            {!! Form::text('image1', $scenery->image1, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('image2', 'Image 2') !!}
            {!! Form::text('image2', $scenery->image1, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('image3', 'Image 3') !!}
            {!! Form::text('image3', $scenery->image1, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
    {!! Form::close() !!}
            <div class="col-sm-1">
                <a href="/dashboard/admin/scenery" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>
@endsection
