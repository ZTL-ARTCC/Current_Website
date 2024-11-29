<div class="container">
    {{ html()->form('POST', '/pilots/scenery/search')->open() }}
        <div class="form-group inline">
            <div class="row">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-9">
                            {{ html()->text('search', null)->placeholder('Search for Airport/Developer')->class(['form-control']) }}
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
    {{ html()->form()->close() }}
    @php ($sceneryCategories = array('fsx' => 'FSX', 'xp' => 'X-Plane', 'afcad' => 'AFCADs'))
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($sceneryCategories as $sceneryCategory => $sceneryDescription)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <li class="nav-item">
                <a class="nav-link tab-link{{ $active }}" href="#{{ $sceneryCategory }}" role="tab" data-toggle="tab" style="color:black">{{ $sceneryDescription }}</a>
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
