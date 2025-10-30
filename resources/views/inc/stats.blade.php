<?php
$mname = date("F", mktime(0, 0, 0, $month, 1, $year));
if ($month == 1) { $pm = 12; $pyr = $year - 1; } else { $pm = $month -1; $pyr = $year; }
if ($month == 12) { $nm = 1; $nyr = $year + 1; } else { $nm = $month + 1; $nyr = $year; }
$base_url = '/controllers/stats';
if (!$privacy)  {
    $base_url = '/dashboard/controllers/stats';
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4 text-center">
            <h3>Total Hours this Month</h3>
            <div class="card py-1 text-center" style="background-color:#d3d3d3">
                <br>
                <h4 class="m-0">{{ number_format($all_stats['month'], 2) }}</h4>
                <br>
            </div>
        </div>
        <div class="col-sm-4 text-center">
            <h3>Total Hours this Year</h3>
            <div class="card py-1 text-center" style="background-color:#d3d3d3">
                <br>
                <h4 class="m-0">{{ number_format($all_stats['year'], 2) }}</h4>
                <br>
            </div>
        </div>
        <div class="col-sm-4 text-center">
            <h3>Total Hours All Time</h3>
            <div class="card py-1 text-center" style="background-color:#d3d3d3">
                <br>
                <h4 class="m-0">{{ number_format($all_stats['total'], 2) }}</h4>
                <br>
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-sm-2">
            <a class="btn btn-primary text-nowrap" href="{{ $base_url }}/<?=$pyr?>/<?=$pm?>"><i class="fa fa-arrow-left"></i> Previous Month</a></li>
        </div>
        <div class="col-sm-8 text-center">
            <h4>Showing Stats for <?=$mname?> 20<?=$year?></h4>
        </div>
        <div class="col-sm-2 text-end">
            <a class="btn btn-primary text-nowrap" href="{{ $base_url }}/<?=$nyr?>/<?=$nm?>">Next Month <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
    <br>
    @php ($statCategories = array('home', 'visiting'))
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($statCategories as $statCategory)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <li class="nav-item">
                <a class="nav-link{{ $active }}" href="#{{ $statCategory }}" role="tab" data-bs-toggle="tab" >{{ ucfirst($statCategory) }} Controllers</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($statCategories as $statCategory)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <div role="tabpanel" class="tab-pane{{ $active }}" id="{{ $statCategory }}">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Local</th>
                        <th scope="col">TRACON</th>
                        <th scope="col">Center</th>
                        <th scope="col">Month Total</th>
                        <th scope="col">Quarter Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($$statCategory as $h)
                        <tr>
                            <td>
                                @if($privacy)    
                                    {{ ucwords($h->backwards_public_name) }}
                                @else
                                    {{ $h->full_name }}
                                @endif
                            </td>
                            <td>{{ $h->rating_short }}</td>
                            <td>{{ $stats[$h->id]->local_hrs }}</td>
                            <td>{{ $stats[$h->id]->approach_hrs }}</td>
                            <td>{{ $stats[$h->id]->enroute_hrs }}</td>
                            <td>{{ $stats[$h->id]->total_hrs }}</td>
                            @if($qtr_stats[$h->id]->total_hrs >= 3)
                                <td class="hours-success black"><b>{{ $qtr_stats[$h->id]->total_hrs }}</b></td>
                            @else
                                <td class="hours-danger black"><b>{{ $qtr_stats[$h->id]->total_hrs }}</b></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endforeach
    </div>
</div>
