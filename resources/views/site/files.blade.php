@extends('layouts.master')

@section('title')
Files
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>ARTCC Files</h2>
        &nbsp;
    </div>
</span>
<br>

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
                <a class="nav-link{{ $activeMarker }}" href="#{{ strtolower($fileCategory) }}" role="tab" data-toggle="tab" style="color:black">{{ $fileCategory }}</a>
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
                    @if($$fileCategory->count() > 0)
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success btn-block simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif
								</div>									
                                </td>
                            @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
</div>
{{Html::script(asset('js/filebrowser.js'))}}
@endsection
