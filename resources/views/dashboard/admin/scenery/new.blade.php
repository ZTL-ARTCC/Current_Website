@extends('layouts.dashboard')

@section('title')
New Scenery
@endsection

@section('content')
@include('inc.header', ['title' => 'New Scenery'])

<div class="container">
    {{ html()->form()->route('AdminDash@storeScenery') }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('Airport Name', 'apt') }}
                    {{ html()->text('apt', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Simulator/AFCAD', 'sim') }}
                    {{ html()->select('sim', [
                        0 => 'FSX/P3D',
                        1 => 'X-Plane',
                        2 => 'AFCAD'
                    ], null)->class(['form-control'])->placeholder('Choose Simulator (Required)') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('Price', 'price') }}
                    {{ html()->text('price', null)->class(['form-control'])->placeholder('If it is free, put "0" (Required)') }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Currency', 'currency') }}
                    {{ html()->text('currency', 'USD')->class(['form-control'])->placeholder('Required') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('Link to Scenery', 'url') }}
                    {{ html()->text('url', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Developer', 'dev') }}
                    {{ html()->text('dev', null)->class(['form-control'])->placeholder('Required') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ html()->label('Image 1', 'image1') }}
            {{ html()->text('image1', null)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            {{ html()->label('Image 2', 'image2') }}
            {{ html()->text('image2', null)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            {{ html()->label('Image 3', 'image3') }}
            {{ html()->text('image3', null)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
    {{ html()->form()->close() }}
            <div class="col-sm-1">
                <a href="/dashboard/admin/scenery" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>
@endsection
