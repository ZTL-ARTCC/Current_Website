@extends('layouts.dashboard')

@section('title')
Airport Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Airport Management</h2>
    &nbsp;
</div>
<br>
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
                                {!! Form::open(['action' => ['AdminDash@deleteAirport', $a->id]]) !!}
                                    @csrf
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-danger simple-tooltip" action="submit" data-toggle="tooltip" title="Delete Airport"><i class="fas fa-times"></i></button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
</div>
@endsection
