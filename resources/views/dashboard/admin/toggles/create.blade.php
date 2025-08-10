@extends('layouts.dashboard')

@section('title')
New Feature Toggle
@endsection

@section('content')
@include('inc.header', ['title' => 'New Feature Toggle'])

<div class="container">
    {{ html()->form()->route('createFeatureToggle')->open() }}
        @csrf
        <div class="form-group mb-3">
            <div class="row">
                <div class="col-sm-6">
                    <label for="toggle_name">Toggle Name</label>
                    {{ html()->text('toggle_name', null)->class(['form-control'])->placeholder('new_toggle_name') }}
                </div>
                <div class="col-sm-6">
                    <label for="toggle_description">Toggle Description</label>
                    {{ html()->text('toggle_description', null)->class(['form-control'])->placeholder('Description') }}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Add</button>
    {{ html()->form()->close() }}
</div>
@endsection
