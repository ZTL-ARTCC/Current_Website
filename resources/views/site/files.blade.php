@extends('layouts.master')

@section('title')
Files
@endsection

@section('content')
@include('inc.header', ['title' => 'ARTCC Files', 'type' => 'external'])

<div class="container">
    @php
        $fileCategories = array('vATIS', 'SOP', 'LOA');
    @endphp 
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($fileCategories as $fileCategory)
            @php ($activeMarker = '')
            @if ($loop->first)
                @php ($activeMarker = ' active')
            @endif
            <li class="nav-item">
                <a class="nav-link tab-link {{ $activeMarker }}" href="#{{ strtolower($fileCategory) }}" role="tab" data-bs-toggle="tab">{{ $fileCategory }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($fileCategories as $fileCategory)
            @php ($fileCategory = strtolower($fileCategory))
            @php ($activeMarker = '')
            @if ($loop->first)
                @php ($activeMarker = ' active')
            @endif
        <div role="tabpanel" class="tab-pane{{ $activeMarker }}" id="{{ $fileCategory }}">
            @if($$fileCategory->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col"><center>Description</center></th>
                            <th scope="col"><center>Uploaded/Updated at</center></th>
                            <th scope="col"><center>Download</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($$fileCategory as $f)
                            <tr>
                            @if($f->row_separator)
                                <th class="text-center" colspan="4">{{ $f->name }}</th>
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
                                <div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success btn-block simple-tooltip" data-bs-toggle="tooltip" title="Download" {{ ($fileCategory == 'vatis') ? 'download' : null }}><i class="fas fa-download fa-fw"></i></a>
                                        @if(!is_null($f->permalink))
                                            <a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-bs-toggle="tooltip" title="Copy Permalink" data-bs-title="asset/{{ $f->permalink }}"><i class="fas fa-link fa-fw"></i></a>
                                        @endif
                                </div>									
                                </td>
                            @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                @include('inc.empty_state', ['header' => 'No Files', 'body' => 'There are no files of this type to show.', 'icon' => 'fa-solid fa-file'])
            @endif
        </div>
        @endforeach
    </div>
</div>
@vite('resources/js/filebrowser.js')
@endsection
