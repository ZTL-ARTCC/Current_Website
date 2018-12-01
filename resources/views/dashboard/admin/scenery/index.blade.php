@extends('layouts.dashboard')

@section('title')
Scenery Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Scenery Management</h2>
    &nbsp;
</div>
<br>
<div class="container">
    <a href="/dashboard/admin/scenery/new" class="btn btn-primary">New Scenery</a>
    <br><br>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#fsx" role="tab" data-toggle="tab" style="color:black">FSX/P3D</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#xp" role="tab" data-toggle="tab" style="color:black">X-Plane</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#afcad" role="tab" data-toggle="tab" style="color:black">AFCAD</a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="fsx">
            @if($fsx != '[]')
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Thumbnail</th>
                            <th scope="col">Scenery Airport</th>
                            <th scope="col">Developer</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                        @foreach($fsx as $s)
                            <tr>
                                <td>
                                    @if($s->image1)
                                        <a href="/dashboard/admin/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
                                    @else
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="100px"></img>
                                    @endif
                                </td>
                                <td>{{ $s->airport }}</td>
                                <td>
                                    <a href="{{ $s->link }}" target="_blank">{{ $s->developer }}</a>
                                </td>
                                <td>
                                    @if($s->price == 0)
                                        Free
                                    @else
                                        {{ $s->price }} {{ $s->currency }}
                                    @endif
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <a href="/dashboard/admin/scenery/edit/{{ $s->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Edit Scenery"><i class="far fa-edit"></i></a>
                                        </div>
                                        <div class="col-sm-2">
                                            {!! Form::open(['action' => ['AdminDash@deleteScenery', $s->id]]) !!}
                                                @csrf
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                <button class="btn btn-danger simple-tooltip" data-toggle="tooltip" action="submit" title="Delete Scenery"><i class="fas fa-times"></i></button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            @else
                <p>No scenery for FSX and P3D.</p>
            @endif
        </div>
        <div role="tabpanel" class="tab-pane" id="xp">
            @if($xp != '[]')
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Thumbnail</th>
                            <th scope="col">Scenery Airport</th>
                            <th scope="col">Developer</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                        @foreach($xp as $s)
                        <tr>
                            <td>
                                @if($s->image1)
                                    <a href="/dashboard/admin/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
                                @else
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="100px"></img>
                                @endif
                            </td>
                            <td>{{ $s->airport }}</td>
                            <td>
                                <a href="{{ $s->link }}" target="_blank">{{ $s->developer }}</a>
                            </td>
                            <td>
                                @if($s->price == 0)
                                    Free
                                @else
                                    {{ $s->price }} {{ $s->currency }}
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <a href="/dashboard/admin/scenery/edit/{{ $s->id }}" class="btn btn-success simple-tooltip" title="Edit Scenery"><i class="far fa-edit"></i></a>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::open(['action' => ['AdminDash@deleteScenery', $s->id]]) !!}
                                            <button class="btn btn-danger simple-tooltip" action="submit" title="Delete Scenery"><i class="fas fa-times"></i></button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </thead>
                </table>
            @else
                <p>No scenery for X-Plane.</p>
            @endif
        </div>
        <div role="tabpanel" class="tab-pane" id="afcad">
            @if($afcad != '[]')
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Scenery Airport</th>
                            <th scope="col">Developer</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                        @foreach($afcad as $s)
                            <tr>
                                <td>{{ $s->airport }}</td>
                                <td>
                                    <a href="{{ $s->link }}" target="_blank">{{ $s->developer }}</a>
                                </td>
                                <td>
                                    @if($s->price == 0)
                                        Free
                                    @else
                                        {{ $s->price }} {{ $s->currency }}
                                    @endif
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <a href="/dashboard/admin/scenery/edit/{{ $s->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Edit Scenery"><i class="far fa-edit"></i></a>
                                        </div>
                                        <div class="col-sm-2">
                                            {!! Form::open(['action' => ['AdminDash@deleteScenery', $s->id]]) !!}
                                                @csrf
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                <button class="btn btn-danger simple-tooltip" data-toggle="tooltip" action="submit" title="Delete Scenery"><i class="fas fa-times"></i></button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            @else
                <p>No AFCADs found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
