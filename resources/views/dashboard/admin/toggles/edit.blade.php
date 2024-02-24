@extends('layouts.dashboard')

@section('title')
Edit Feature Toggle
@endsection

@section('content')
<div class="container-fluid view-header">
    <h2>Edit Feature Toggle</h2>
</div>
<br>
<div class="container">
    {!! Form::open(['action' => 'AdminDash@editFeatureToggle']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('toggle_name', 'Toggle Name') !!}
                    {!! Form::text('toggle_name', $t->toggle_name, ['class' => 'form-control', 'placeholder' => 'new_toggle_name']) !!}
                    {!! Form::hidden('toggle_name_orig', $t->toggle_name) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('toggle_description', 'Toggle Description') !!}
                    {!! Form::text('toggle_description', $t->toggle_description, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Save</button>
    {!! Form::close() !!}
</div>
@endsection
