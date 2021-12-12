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
    @if(Auth::user()->can('files'))
        <a href="/dashboard/admin/files/upload" class="btn btn-primary">Upload File</a>
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
        @if(Auth::user()->can('staff'))
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
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
                                <td>{{ $f->name }}</td>
                                <td>{{ $f->desc }}</td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
								<div class="btn-group">
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
										@if(!$loop->first)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Up"><i class="fas fa-arrow-up"></i></a>
										@endif
										@if(!$loop->last)
											<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="Down"><i class="fas fa-arrow-down"></i></a>
										@endif
                                    @endif
								</div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
		<script>
		function itemReorder(id,pos,typ,act) { // Handles custom re-ordering of items in file browser
			//alert(act.title);
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
		</script>
    </div>
</div>
@endsection
