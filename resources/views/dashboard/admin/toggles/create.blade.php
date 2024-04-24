@extends('layouts.dashboard')

@section('title')
New Feature Toggle
@endsection

@section('content')
@include('inc.header', ['title' => 'New Feature Toggle'])

<div class="container">
    {{ html()->form()->route('AdminDash@createFeatureToggle') }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('Toggle Name', 'toggle_name') }}
                    {{ html()->text('toggle_name', null)->class(['form-control'])->placeholder('new_toggle_name') }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Toggle Description', 'toggle_description') }}
                    {{ html()->text('toggle_description', null)->class(['form-control'])->placeholder('Description') }}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Add</button>
    {{ html()->form()->close() }}
</div>
@endsection
