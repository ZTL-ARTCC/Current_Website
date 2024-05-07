@extends('layouts.dashboard')

@section('title')
Edit Scenery
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Scenery'])

<div class="container">
    {{ html()->form()->route('AdminDash@saveScenery', [$scenery->id]) }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="apt">Airport Name</label>
                    {{ html()->text('apt', $scenery->airport)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    <label for="sim">Simulator/AFCAD</label>
                    {{ html()->select('sim', [
                        0 => 'FSX/P3D',
                        1 => 'X-Plane',
                        2 => 'AFCAD'
                    ], $scenery->sim)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="url">Link to Scenery</label>
                    {{ html()->text('url', $scenery->link)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    <label for="dev">Developer</label>
                    {{ html()->text('dev', $scenery->developer)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="image1">Image 1</label>
            {{ html()->text('image1', $scenery->image1)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            <label for="image2">Image 2</label>
            {{ html()->text('image2', $scenery->image1)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            <label for="image3">Image 3</label>
            {{ html()->text('image3', $scenery->image1)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
    {{ html()->form()->close() }}
            <div class="col-sm-1">
                <a href="/dashboard/admin/scenery" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>
@endsection
