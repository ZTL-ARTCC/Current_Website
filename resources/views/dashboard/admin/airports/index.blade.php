@extends('layouts.dashboard')

@section('title')
Airport Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Airport Management'])

<div class="container">
    <a href="/dashboard/admin/airports/new" class="btn btn-primary">New Airport</a>
    <br><br>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Airport Name</th>
                <th scope="col">ICAO Identifier</th>
                <th scope="col">Actions</th>
            </tr>
            @foreach($airports as $a)
                <tr>
                    <td>{{ $a->name }}</td>
                    <td>{{ $a->ltr_4 }}</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-2">
                                @if($a->front_pg == 1)
                                    <a href="/dashboard/admin/airports/del-from-home/{{ $a->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Remove from Home Page"><i class="fas fa-minus"></i></a>
                                @else
                                    <a href="/dashboard/admin/airports/add-to-home/{{ $a->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add to Home Page"><i class="fas fa-plus"></i></a>
                                @endif
                            </div>
                            <div class="col-sm-2">
                                {{ html()->form('DELETE')->route('AdminDash@deleteAirport', [$a->id]) }}
                                    @csrf
                                    <button class="btn btn-danger simple-tooltip" action="submit" data-toggle="tooltip" title="Delete Airport"><i class="fas fa-times"></i></button>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
</div>
@endsection
