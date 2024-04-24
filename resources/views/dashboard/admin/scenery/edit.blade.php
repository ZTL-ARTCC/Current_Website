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
                    {{ html()->label('Airport Name', 'apt') }}
                    {{ html()->text('apt', $scenery->airport)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Simulator/AFCAD', 'sim') }}
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
                    {{ html()->label('Link to Scenery', 'url') }}
                    {{ html()->text('url', $scenery->link)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Developer', 'dev') }}
                    {{ html()->text('dev', $scenery->developer)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ html()->label('Image 1', 'image1') }}
            {{ html()->text('image1', $scenery->image1)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            {{ html()->label('Image 2', 'image2') }}
            {{ html()->text('image2', $scenery->image1)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            {{ html()->label('Image 3', 'image3') }}
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
