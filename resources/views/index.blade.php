@extends('layout')
@section('title', 'Welcome')
@section('content')

<div class="c-wrapper">
<div class="container">
        <div class="jumbotron" style="background-image:url(/photos/ZTL_Banner.jpg); background-size:cover; background-repeat:no-repeat;">>
            <div class="row">
                <div class="col-sm-8">
                    <div class="OutlineText">
                    <h1 class="OutlineText" style="
	font: Tahoma, Geneva, sans-serif;
	font-size: 64px;
    color: white;
    text-shadow:
    /* Outline */
    -2px -2px 0 #000000,
    1px -1px 0 #000000,
    -1px 1px 0 #000000,
    2px 2px 0 #000000,  
    -2px 0 0 #000000,
    2px 0 0 #000000,
    0 2px 0 #000000,
    0 -2px 0 #000000; /* Terminate with a semi-colon */"><b>Atlanta Virtual ARTCC</b></h1>
                    </div>
                </div>
                <div class="col-sm-4">
                    @if($atl_ctr === 1)
                        <div class="alert alert-success">Atlanta Center is ONLINE</div>
                    @else
                        <div class="alert alert-danger">Atlanta Center is OFFLINE</div>
                    @endif
                    @if($atl_app === 1)
                        <div class="alert alert-success">A80 TRACON is ONLINE</div>
                    @else
                        <div class="alert alert-danger">A80 TRACON is OFFLINE</div>
                    @endif
                    @if($atl_twr === 1)
                        <div class="alert alert-success">Atlanta ATCT is ONLINE</div>
                    @else
                        <div class="alert alert-danger">Atlanta ATCT is OFFLINE</div>
                    @endif
                    @if($clt_twr === 1)
                        <div class="alert alert-success">Charlotte ATCT is ONLINE</div>
                    @else
                        <div class="alert alert-danger">Charlotte ATCT is OFFLINE</div>
                    @endif
                </div>
            </div>
        </div>
    </div>


<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

<div class="carousel-inner" role="listbox">

   
   

       

        <div class="carousel-caption"></div>

    </div>

  

</div>

</div>

</div>
<br><p><br></p>
<div class="container">

<br>

<div col="row">
<br><br>
    <div class="col-md-12 alert alert-info link-underline">

    @if($announcement->body != null)
        
            {!! $announcement->body !!}
            <hr>
            <p class="small"><i>Last updated by {{ $announcement->staff_name }} on {{ $announcement->update_time }}</i></p>
     
        <hr>
    @endif

    </div>

</div>

<br>

<div class="row">

    <div class="col-md-6">

        <div class="panel panel-default">

            <div class="panel-heading">

                <h3 class="panel-title">

                    Recent News

                </h3>

            </div>
           
            <div class="panel-body">

                <table id="newsbody">

                @if(count($news) > 0)
                @foreach($news as $c)
                <p>{{ $c->date }} - <b>{{ $c->title }}</b> <a href="/dashboard/controllers/calendar/view/{{ $c->id }}">(View)</a></p>
                @endforeach
            @else
                <center><i><p>No news to show.</p></i></center>
            @endif

                </table>
               
            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="panel panel-default">

            <div class="panel-heading">

                <h3 class="panel-title">

                    Upcoming Events

                </h3>

            </div>
           
           


            
            <div class="panel-body">

               
                @if($events->count() > 0)
                @foreach($events as $e)
                    <a href="/dashboard/controllers/events/view/{{ $e->id }}"><img src="{{ $e->banner_path }}" width="100%" alt="{{ $e->name }}"></a>
                    <p></p>
                @endforeach
            @else
                <center><i><p>No events to show.</p></i></center>
            @endif


            </div>

        </div>

    </div>
    

</div>

</div>

@stop