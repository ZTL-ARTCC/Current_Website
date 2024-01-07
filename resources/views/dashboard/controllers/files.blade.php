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
    @php
        $fileCategories = array('vATIS', 'SOP', 'LOA', 'Training', 'Staff');
        $restricted = array('Staff'=>'staff', 'Training'=>'train');
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
                <a class="nav-link{{ $activeMarker }}" href="#{{ strtolower($fileCategory) }}" role="tab" data-toggle="tab" style="color:black">{{ $fileCategory }}</a>
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
                        @if($$displayedTab->count() > 0)
                            @foreach($$displayedTab as $f)
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
        @endforeach
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
                                        3 => 'vATIS',
                                        4 => 'SOPs',
                                        5 => 'LOAs',
                                        6 => 'Staff',
                                        7 => 'Training'
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
    </div>
</div>
{{Html::script(asset('js/filebrowser.js'))}}
@endsection
