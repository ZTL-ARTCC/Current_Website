@extends('layouts.dashboard')

@section('title')
New Scenery
@endsection

@section('content')
@include('inc.header', ['title' => 'New Scenery'])

<div class="container">
    {{ html()->form()->route('storeScenery') }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="apt">Airport Name</label>
                    {{ html()->text('apt', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    <label for="sim">Simulator/AFCAD</label>
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
                    <label for="price">Price</label>
                    {{ html()->text('price', null)->class(['form-control'])->placeholder('If it is free, put "0" (Required)') }}
                </div>
                <div class="col-sm-6">
                    <label for="currency">Currency</label>
                    {{ html()->text('currency', 'USD')->class(['form-control'])->placeholder('Required') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="url">Link to Scenery</label>
                    {{ html()->text('url', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    <label for="dev">Developer</label>
                    {{ html()->text('dev', null)->class(['form-control'])->placeholder('Required') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="image1">Image 1</label>
            {{ html()->text('image1', null)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            <label for="image2">Image 2</label>
            {{ html()->text('image2', null)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            <label for="image3">Image 3</label>
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
