@extends('layouts.dashboard')

@section('title')
Scenery Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Scenery Management'])

<div class="container">
    <a href="/dashboard/admin/scenery/new" class="btn btn-primary">New Scenery</a>
    <br><br>
    @php ($sceneryCategories = array('fsx' => 'FSX', 'xp' => 'X-Plane', 'afcad' => 'AFCADs'))
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($sceneryCategories as $sceneryCategory => $sceneryDescription)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <li class="nav-item">
                <a class="nav-link tab-link{{ $active }}" href="#{{ $sceneryCategory }}" role="tab" data-bs-toggle="tab" >{{ $sceneryDescription }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($sceneryCategories as $sceneryCategory => $sceneryDescription)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <div role="tabpanel" class="tab-pane{{ $active }}" id="{{ $sceneryCategory }}">
            @if($$sceneryCategory != '[]')
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Thumbnail</th>
                            <th scope="col">Scenery Airport</th>
                            <th scope="col">Developer</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                        @foreach($$sceneryCategory as $s)
                            <tr>
                                <td>
                                    @if($s->image1)
                                        <a href="/pilots/scenery/view/{{ $s->id }}"><img src="{{ $s->image1 }}" width="100px"></img></a>
                                    @else
                                        <img src="/photos/No_image_available.svg" width="100px"></img>
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
                                            <a href="/dashboard/admin/scenery/edit/{{ $s->id }}" class="btn btn-success simple-tooltip" data-bs-toggle="tooltip" title="Edit Scenery"><i class="far fa-edit"></i></a>
                                        </div>
                                        <div class="col-sm-2">
                                            {{ html()->form('DELETE')->route('deleteScenery', [$s->id])->open() }}
                                                @csrf
                                                <button class="btn btn-danger simple-tooltip" data-bs-toggle="tooltip" action="submit" title="Delete Scenery"><i class="fas fa-times"></i></button>
                                            {{ html()->form()->close() }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            @else
                @include('inc.empty_state', ['header' => 'No Scenery', 'body' => 'No scenery currently exists for this simulator type.', 'icon' => 'fa fa-plane'])
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
