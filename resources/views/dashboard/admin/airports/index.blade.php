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
                        @if($a->id != 1 && $a->id != 2 && $a->id != 3)
                            {!! Form::open(['action' => ['AdminDash@deleteAirport', $a->id]]) !!}
                                @csrf
                                {!! Form::hidden('_method', 'DELETE') !!}
                                <button class="btn btn-danger simple-tooltip" action="submit" data-toggle="tooltip" title="Delete Airport"><i class="fa fa-times"></i></button>
                            {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
</div>
@endsection
