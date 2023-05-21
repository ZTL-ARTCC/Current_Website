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

    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#vrc" role="tab" data-toggle="tab" style="color:black">VRC</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#vstars" role="tab" data-toggle="tab" style="color:black">vSTARS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#veram" role="tab" data-toggle="tab" style="color:black">vERAM</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#vatis" role="tab" data-toggle="tab" style="color:black">vATIS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#sop" role="tab" data-toggle="tab" style="color:black">SOPs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#loa" role="tab" data-toggle="tab" style="color:black">LOAs</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="vrc">
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
                    @if($vrc->count() > 0)
                        @foreach($vrc as $f)
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
        <div role="tabpanel" class="tab-pane" id="vstars">
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
                    @if($vstars->count() > 0)
                        @foreach($vstars as $f)
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
        <div role="tabpanel" class="tab-pane" id="veram">
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
                    @if($veram->count() > 0)
                        @foreach($veram as $f)
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
        <div role="tabpanel" class="tab-pane" id="vatis">
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
                    @if($vatis->count() > 0)
                        @foreach($vatis as $f)
                            <tr>
                            @if($f->row_separator)
                                    <th class="text-center" colspan="4">{{ $f->name }}</th>
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" <?php if(pathinfo($f->path)['extension'] == 'json') { print " download=\"" . basename($f->path) . "\""; } ?> target="_blank" class="btn btn-success btn-block simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
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
        <div role="tabpanel" class="tab-pane" id="sop">
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
                    @if($sop->count() > 0)
                        @foreach($sop as $f)
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
        <div role="tabpanel" class="tab-pane" id="loa">
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
                    @if($loa->count() > 0)
                        @foreach($loa as $f)
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
		<script>
	function fallbackCopyTextToClipboard(text) {
		var textArea = document.createElement("textarea");
		textArea.value = text;
  
		// Avoid scrolling to bottom
		textArea.style.top = "0";
		textArea.style.left = "0";
		textArea.style.position = "fixed";

		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			var successful = document.execCommand('copy');
			var msg = successful ? 'successful' : 'unsuccessful';
			//console.log('Fallback: Copying text command was ' + msg);
		} catch (err) {
			//console.error('Fallback: Oops, unable to copy', err);
		}

		document.body.removeChild(textArea);
	}
	
	function copyTextToClipboard(text) {
		if (!navigator.clipboard) {
			fallbackCopyTextToClipboard(text);
			return;
		}
		navigator.clipboard.writeText(text).then(function() {
			//console.log('Async: Copying to clipboard was successful!');
		}, function(err) {
			//console.error('Async: Could not copy text: ', err);
		});
	}
	
	function linkToClipboard(e) {
		var path = getSiteRoot() + e.dataset.title;
		copyTextToClipboard(path);
	}
	
	function getSiteRoot() {
		var rootPath = window.location.protocol + "//" + window.location.host + "/";
		if (window.location.hostname == "localhost") {
			var path = window.location.pathname;
			if (path.indexOf("/") == 0) {
				path = path.substring(1);
			}
			path = path.split("/", 1);
			if (path != "") {
				rootPath = rootPath + path + "/";
			}
		}
		return rootPath;
	}
		</script>
    </div>
</div>
@endsection
