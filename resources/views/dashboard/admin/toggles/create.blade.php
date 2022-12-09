@extends('layouts.dashboard')

@section('title')
New Feature Toggle
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>New Feature Toggle</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => 'AdminDash@createFeatureToggle']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('toggle_name', 'Toggle Name') !!}
                    {!! Form::text('toggle_name', null, ['class' => 'form-control', 'placeholder' => 'new_toggle_name']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('toggle_description', 'Toggle Description') !!}
                    {!! Form::text('toggle_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Add</button>
    {!! Form::close() !!}
</div>
@endsection
