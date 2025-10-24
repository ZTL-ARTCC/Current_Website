@extends('layouts.dashboard')

@section('title')
Files
@endsection

@section('content')
@include('inc.header', ['title' => 'Files'])

<div class="container">
    @if(Auth::user()->isAbleTo('files'))
        <a href="/dashboard/admin/files/upload" class="btn btn-primary">Upload File</a>
        &nbsp;
        <span data-bs-toggle="modal" data-bs-target="#addRowSeparator">
            <button type="button" class="btn btn-primary" data-bs-placement="top">Add Separator</button>
        </span>
        <br><br>
    @endif
    @php
        $fileCategories = array('vATIS', 'SOP', 'LOA', 'Training', 'Staff', 'Marketing');
        $restricted = array('Staff'=>'staff', 'Training'=>'train', 'Marketing'=>'contributor');
        $displayedTabs = array();
    @endphp 
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($fileCategories as $fileCategory)
            @php ($activeMarker = '')
            @if (in_array($fileCategory, array_keys($restricted)))
                @if (!Auth::user()->isAbleTo($restricted[$fileCategory]))
                    @if (!Auth::user()->isAbleTo('staff'))
                        @continue
                    @endif
                @endif
            @endif
            @if (count($displayedTabs) == 0)
                @php ($activeMarker = ' active')
            @endif
            <li class="nav-item">
                <a class="nav-link{{ $activeMarker }}" href="#{{ strtolower($fileCategory) }}" role="tab" data-bs-toggle="tab" style="color:black">{{ $fileCategory }}</a>
            </li>
            @php ($displayedTabs[] = strtolower($fileCategory))
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($displayedTabs as $displayedTab)
            @php ($activeMarker = '')
            @if ($loop->first)
                @php ($activeMarker = ' active')
            @endif
            <div role="tabpanel" class="tab-pane{{ $activeMarker }}" id="{{ $displayedTab }}">
                @if($$displayedTab->count() > 0)
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col"><center>Description</center></th>
                                <th scope="col"><center>Uploaded/Updated at</center></th>
                                <th scope="col"><center>Actions</center></th>
                            </tr>
                        </thead>
                        @if(!isset($$displayedTab))
                    </table>
                </div>
                            @continue
                        @endif
                        <tbody>
                            @foreach($$displayedTab as $f)
                                <tr>
                                @if($f->row_separator)
                                    @if(Auth::user()->isAbleTo('files'))
                                        <th class="text-center" colspan="3">{{ $f->name }}</th>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt fa-fw"></i></a>
                                                <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-bs-toggle="tooltip" title="Delete"><i class="fas fa-times fa-fw"></i></a>
                                                @if(!$loop->first)
      									    	    <a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-bs-toggle="tooltip" title="Up"><i class="fas fa-arrow-up fa-fw"></i></a>
	    								        @endif
    		    							    @if(!$loop->last)
	    		    							    <a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-bs-toggle="tooltip" title="Down"><i class="fas fa-arrow-down fa-fw"></i></a>
		    		    					    @endif
                                            </div>
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
                                            <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-bs-toggle="tooltip" title="Download" {{ ($displayedTab == 'vatis') ? 'download' : null }}><i class="fas fa-download fa-fw"></i></a>
                                            @if(Auth::user()->isAbleTo('files'))
                                                <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt fa-fw"></i></a>
                                                <a href="/dashboard/admin/files/delete/{{ $f->id }}" onclick="return confirm('Are you sure you want to delete {{ $f->name }}?')" class="btn btn-danger simple-tooltip" data-bs-toggle="tooltip" title="Delete"><i class="fas fa-times fa-fw"></i></a>
						    			    	@if(!is_null($f->permalink))
							    			    	<a onclick="linkToClipboard(this);" class="btn btn-secondary simple-tooltip" data-bs-toggle="tooltip" title="Copy Permalink" data-bs-title="asset/{{ $f->permalink }}"><i class="fas fa-link fa-fw"></i></a>
    							    			@endif										
	    							    		@if(!$loop->first)
		    							    		<a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'up');" class="btn btn-info simple-tooltip" data-bs-toggle="tooltip" title="Up"><i class="fas fa-arrow-up fa-fw"></i></a>
			    							    @endif
				    						    @if(!$loop->last)
					    						    <a onclick="itemReorder({{ $f->id }},{{ $loop->index }},{{ $f->type }},'down');" class="btn btn-info simple-tooltip" data-bs-toggle="tooltip" title="Down"><i class="fas fa-arrow-down fa-fw"></i></a>
						    				    @endif
                                            @endif
								        </div>
                                   </td>
                                @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @include('inc.empty_state', ['header' => 'No Files', 'body' => 'There are no files listed for that category.', 'icon' => 'fa-solid fa-file'])
                @endif
            </div>
        @endforeach
        @if(Auth::user()->isAbleTo('files'))
		<div class="modal fade" id="addRowSeparator" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add a Row Separator</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
                    {{ html()->form()->route('fileSeparator')->open() }}
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
                                    <label for="title">File Separator Heading</label>
									{{ html()->text('title', null)->placeholder('Enter heading title')->class(['form-control']) }}
								</div>
                                <div class="col-sm-6">
                                    <label for="type">Tab:</label>
                                    {{ html()->select('type', [
                                        3 => 'vATIS',
                                        4 => 'SOPs',
                                        5 => 'LOAs',
                                        6 => 'Staff',
                                        7 => 'Training',
                                        8 => 'Marketing'
                                    ], null)->class(['form-select']) }}
                                </div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button action="submit" class="btn btn-success">Save Separator</button>
					</div>
					{{ html()->form()->close() }}
				</div>
			</div>
		</div>
        @endif
    </div>
</div>
@vite('resources/js/filebrowser.js')
@endsection
