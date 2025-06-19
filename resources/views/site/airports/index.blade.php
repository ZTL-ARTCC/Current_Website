@extends('layouts.master')

@section('title')
Airports
@endsection

@section('content')
@include('inc.header', ['title' => 'Airports', 'type' => 'external'])

<div class="container">
    @if(count($airports) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Airport Name</th>
                    <th scope="col">Latest METAR</th>
                    <th scope="col">Visual Conditions</th>
                </tr>
                @foreach($airports as $a)
                    <tr>
                        <td><a href="/pilots/airports/view/{{ $a->id }}">{{ $a->name }} ({{ $a->ltr_3 }})</a></td>
                        <td>{{ $a->metar }}</td>
                        <td>{{ $a->visual_conditions }}</td>
                    </tr>
                @endforeach
            </thead>
        </table>
    @else
        @include('inc.empty_state', ['header' => 'No Airports', 'body' => 'There are no airports to display matching that search criteria.', 'icon' => 'fa fa-plane'])
    @endif
</div>
@endsection
