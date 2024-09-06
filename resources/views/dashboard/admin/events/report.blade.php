@extends('layouts.dashboard')

@section('title')
Event Statistics Report
@endsection

@section('content')
@include('inc.header', ['title' => 'Event Statistics Report'])

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h4>Controller Counts</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S1</th>
                        <th>S2</th>
                        <th>S3</th>
                        <th>C1</th>
                        <th>C3</th>
                        <th>I1</th>
                        <th>I3</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $event_stat->controllers_by_rating['S1'] }}</td>
                        <td>{{ $event_stat->controllers_by_rating['S2'] }}</td>
                        <td>{{ $event_stat->controllers_by_rating['S3'] }}</td>
                        <td>{{ $event_stat->controllers_by_rating['C1'] }}</td>
                        <td>{{ $event_stat->controllers_by_rating['C3'] }}</td>
                        <td>{{ $event_stat->controllers_by_rating['I1'] }}</td>
                        <td>{{ $event_stat->controllers_by_rating['I3'] }}</td>
                        <th>{{ array_sum($event_stat->controllers_by_rating) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4>Airport Movements</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Airport</th>
                        <th>Arrivals</th>
                        <th>Departures</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event_stat->movements as $airport => $movements)
                        <tr>
                            <td>{{ $airport }} </td>
                            <td>{{ $movements["arrivals"] }}</td>
                            <td>{{ $movements["departures"] }}</td>
                            <th>{{ array_sum($movements) }}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <a href="javascript:history.back()" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
</div>
@endsection
