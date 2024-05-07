@extends('layouts.dashboard')

@section('title')
Edit Feature Toggle
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Feature Toggle'])

<div class="container">
    {{ html()->form()->route('AdminDash@editFeatureToggle') }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="toggle_name">Toggle Name</label>
                    {{ html()->text('toggle_name', $t->toggle_name)->class(['form-control'])->placeholder('new_toggle_name') }}
                    {{ html()->hidden('toggle_name_orig', $t->toggle_name) }}
                </div>
                <div class="col-sm-6">
                    <label for="toggle_description">Toggle Description</label>
                    {{ html()->text('toggle_description', $t->toggle_description)->class(['form-control'])->placeholder('Description') }}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Save</button>
    {{ html()->form()->close() }}
</div>
@endsection
