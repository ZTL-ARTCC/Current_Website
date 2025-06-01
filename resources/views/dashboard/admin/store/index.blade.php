@extends('layouts.dashboard')

@section('title')
Merch Store Management
@endsection

@section('content')
@include('inc.header', ['title' => 'ZTL Merch Store'])

<div class="container">
    <a href="/dashboard/admin/store/new" class="btn btn-primary">Create New Item</a>
    <br><br>
    @if(count($merch_items) > 0)
        <table class="table table-outline">
            <thead>
                <tr>
                    <th scope="col" colspan="2">Item</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                </tr>
                @foreach($merch_items as $merch_item)
                    <tr>
                        <td>
                            @if($merch_item->image)
                                <img src="{{ url('public/store/'.$merch_item->image) }}" width="100px"></img>
                            @else
                                <img src="/photos/No_image_available.svg" width="100px"></img>
                            @endif
                        </td>
                        <td>
                            @if(!is_null($merch_item->flag))
                                <span class="badge badge-{{ $merch_item->displayFlag()->badge }}">{{ $merch_item->displayFlag()->text }}</span><br>
                            @endif
                            {{ $merch_item->title }}
                        </td>
                        <td>{{ $merch_item->description }}</td>
                        <td>${{ $merch_item->price }} USD</td>
                        <td>
                            <div class="row">
                                <div class="col-sm-3">
                                    <a href="/dashboard/admin/store/edit/{{ $merch_item->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Edit Item"><i class="far fa-edit"></i></a>
                                </div>
                                <div class="col-sm-2">
                                    {{ html()->form('DELETE')->route('deleteStoreItem', [$merch_item->id])->open() }}
                                        @csrf
                                        <button class="btn btn-danger simple-tooltip" data-toggle="tooltip" action="submit" title="Delete Item"><i class="fas fa-times"></i></button>
                                    {{ html()->form()->close() }}
                                </div>
                            </div>
                        </td>
                    </tr>
                 @endforeach
            </thead>
        </table>
    @else
        @include('inc.empty_state', ['header' => 'No Merch', 'body' => 'Sorry - no ZTL merch available today! Please check back soon.', 'icon' => 'fa-solid fa-shirt'])
    @endif
</div>
@endsection
