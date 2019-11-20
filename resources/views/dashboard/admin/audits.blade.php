@extends('layouts.dashboard')

@section('title')
Website Activity
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Website Activity Audit</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <br>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Time</th>
                <th scope="col">Description</th>
                <th scope="col">IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $a)
                <tr>
                    <td>{{ $a->time_date }}</td>
                    <td>{{ $a->what }}</td>
                    <td>{{ $a->ip }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $audits->links() !!}
</div>

@endsection
