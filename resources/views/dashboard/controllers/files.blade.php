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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
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
                                    <a href="{{ $f->path }}" target="_blank" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->can('files'))
                                        <a href="/dashboard/admin/files/edit/{{ $f->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/dashboard/admin/files/delete/{{ $f->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
