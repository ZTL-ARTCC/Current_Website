@extends('layouts.master')

@section('title')
Scenery
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Scenery</h2>
        &nbsp;
    </div>
</span>
<br>
<div class="container">
    {!! Form::open(['url' => '/pilots/scenery/search']) !!}
        <div class="form-group inline">
            <div class="row">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-9">
                            {!! Form::text('search', null, ['placeholder' => 'Search for Airport/Developer', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-3">
                            <button action="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                </div>
                <div class="col-sm-4">
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#fsx" role="tab" data-toggle="tab" style="color:black">FSX/P3D</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#xp" role="tab" data-toggle="tab" style="color:black">X-Plane</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#afcad" role="tab" data-toggle="tab" style="color:black">AFCADs</a>
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
                        </tr>
                        @foreach($fsx as $s)
                            <tr>
                                <td>
                                    @if($s->image1)
                                        <a href="/pilots/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
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
                        </tr>
                        @foreach($xp as $s)
                        <tr>
                            <td>
                                @if($s->image1)
                                    <a href="/pilots/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
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
