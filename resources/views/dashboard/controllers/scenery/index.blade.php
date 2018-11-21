@extends('layouts.dashboard')

@section('title')
Scenery
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h3>Scenery</h3>
    &nbsp;
</div>
<br>
<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#fsx" role="tab" data-toggle="tab" style="color:black">FSX/P3D</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#xp" role="tab" data-toggle="tab" style="color:black">X-Plane</a>
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
                                        <a href="/dashboard/controllers/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
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
                                    <a href="/dashboard/controllers/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
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
    </div>
</div>
@endsection
