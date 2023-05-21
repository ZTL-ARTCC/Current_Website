@extends('layouts.dashboard')

@section('title')
Files
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Files</h2>
    &nbsp;
</div>
<br>

<div class="container">
    @if(Auth::user()->isAbleTo('files'))
        <a href="/dashboard/admin/files/upload" class="btn btn-primary">Upload File</a>
        &nbsp;
        <span data-toggle="modal" data-target="#addRowSeparator">
            <button type="button" class="btn btn-primary" data-placement="top">Add Separator</button>
        </span>
        <br><br>
    @endif

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
        @if(Auth::user()->isAbleTo('staff'))
            <li class="nav-item">
                <a class="nav-link" href="#staff" role="tab" data-toggle="tab" style="color:black">Staff</a>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="vrc">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Description</center></th>
                        <th scope="col"><center>Uploaded/Updated at</center></th>
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($vrc->count() > 0)
                        @foreach($vrc as $f)
                            <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
      										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	    								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                @else
                                    <th class="text-center" colspan="4">{{ $f->name }}</th>
                                @endif
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif										
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($vstars->count() > 0)
                        @foreach($vstars as $f)
                            <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
      										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	       								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                    @else
                                        <th class="text-center" colspan="4">{{ $f->name }}</th>
                                    @endif
                                @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif										
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($veram->count() > 0)
                        @foreach($veram as $f)
                            <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
	  										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	    								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                    @else
                                        <th class="text-center" colspan="4">{{ $f->name }}</th>
                                    @endif
                                @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif										
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($vatis->count() > 0)
                        @foreach($vatis as $f)
                        <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
    										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	    								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                @else
                                    <th class="text-center" colspan="4">{{ $f->name }}</th>
                                @endif
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" <?php if(pathinfo($f->path)['extension'] == 'json') { print " download=\"" . basename($f->path) . "\""; } ?> target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif										
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($sop->count() > 0)
                        @foreach($sop as $f)
                            <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
       										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	    								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                @else
                                    <th class="text-center" colspan="4">{{ $f->name }}</th>
                                @endif
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif										
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($loa->count() > 0)
                        @foreach($loa as $f)
                            <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
      										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	    								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                    @else
                                        <th class="text-center" colspan="4">{{ $f->name }}</th>
                                    @endif
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
        <div role="tabpanel" class="tab-pane" id="staff">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Description</center></th>
                        <th scope="col"><center>Uploaded/Updated at</center></th>
                        <th scope="col"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if($staff->count() > 0)
                        @foreach($staff as $f)
                            <tr>
                            @if($f->row_separator)
                                @if(Auth::user()->isAbleTo('files'))
                                    <th class="text-center" colspan="3">{{ $f->name }}</th>
                                    <td>
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                        @if(!$loop->first)
      										<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
	    								@endif
		    							@if(!$loop->last)
			    							<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
				    					@endif
                                    </td>
                                @else
                                    <th class="text-center" colspan="4">{{ $f->name }}</th>
                                @endif
                            @else
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->isAbleTo('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!is_null($f->permalink))
											<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-toggle="tooltip" title="Copy Permalink" data-title="asset/{{ $f->permalink }}"><i class="fas fa-link"></i></a>
										@endif
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
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
        @if(Auth::user()->isAbleTo('files'))
		<div class="modal fade" id="addRowSeparator" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add a Row Separator</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					{!! Form::open(['action' => ['AdminDash@fileSeparator']]) !!}
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									{!! Form::label('title', 'File Separator Heading') !!}
									{!! Form::text('title', null, ['placeholder' => 'Enter heading title', 'class' => 'form-control']) !!}
								</div>
                                <div class="col-sm-6">
                                    {!! Form::label('type', 'Tab:') !!}
                                    {!! Form::select('type', [
                                        0 => 'VRC',
                                        1 => 'vSTARS',
                                        2 => 'vERAM',
                                        3 => 'vATIS',
                                        4 => 'SOPs',
                                        5 => 'LOAs',
                                        6 => 'Staff'
                                    ], null, ['class' => 'form-control']) !!}
                                </div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Save Separator</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
        @endif
		<script>
		function itemReorder(id,pos,typ,act) { // Handles custom re-ordering of items in file browser
			var dType = '';
			switch(typ) {
				case 0 : dType = 'vrc'; break;
				case 1 : dType = 'vstars'; break;
				case 2 : dType = 'veram'; break;
				case 3 : dType = 'vatis'; break;
				case 4 : dType = 'sop'; break;
				case 5 : dType = 'loa'; break;
				case 6 : dType = 'staff'; break;
			}
			
			$.get('/dashboard/admin/files/disp-order?id=' + id + '&pos=' + pos + '&act=' + act + '&typ=' + typ, function(data) {
				if(data.length > 0) {
					document.getElementById(dType).getElementsByTagName('tbody')[0].innerHTML = data.replace(/\\/g, '');
				}
			});
		}
		
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
