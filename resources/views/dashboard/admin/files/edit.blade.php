@extends('layouts.dashboard')

@section('title')
Upload File
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit File'])

<div class="container">
    {{ html()->form()->route('saveFile', [$file->id])->open() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="title">Title:</label>
                    {{ html()->text('title', $file->name)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    <label for="type">Type:</label>
                    {{ html()->select('type', [
                        3 => 'vATIS',
                        4 => 'SOPs',
                        5 => 'LOAs',
                        6 => 'Staff',
                        7 => 'Training'
                    ], $file->type)->class(['form-control']) }}
                </div>
            </div>
        </div>
        @if(! $file->row_separator)
            <div class="form-group">
                <label for="desc">Description:</label>
                {{ html()->textarea('desc', $file->desc)->class(['form-control'])->placeholder('Optional') }}
            </div>
            <div class="form-group">
                <label for="permalink">Permalink:</label>
                {{ html()->text('permalink', $file->permalink)->class(['form-control'])->placeholder('Optional, no spaces') }}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" action="submit">Save</button>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-danger" href="/dashboard/controllers/files">Cancel</a>
            </div>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
