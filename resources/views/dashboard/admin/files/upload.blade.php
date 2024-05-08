@extends('layouts.dashboard')

@section('title')
Upload File
@endsection

@section('content')
@include('inc.header', ['title' => 'Upload New File'])

<div class="container">
    {{ html()->form()->route('storeFile')->acceptsFiles()->open() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="title">Title (Please refrain from using slashes in the title):</label>
                    {{ html()->text('title', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    <label for="type">Type:</label>
                    {{ html()->select('type', [
                        3 => 'vATIS',
                        4 => 'SOPs',
                        5 => 'LOAs',
                        6 => 'Staff',
                        7 => 'Training'
                    ], null)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="desc">Description:</label>
            {{ html()->textarea('desc', null)->class(['form-control'])->placeholder('Optional') }}
        </div>
        <div class="form-group">
            {{ html()->file('file')->class(['form-control']) }}
        </div>
        <div class="form-group">
            <label for="permalink">Permalink:</label>
            {{ html()->text('permalink', null)->class(['form-control'])->placeholder('Optional, no spaces') }}
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" action="submit">Submit</button>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-danger" href="/dashboard/controllers/files">Cancel</a>
            </div>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
