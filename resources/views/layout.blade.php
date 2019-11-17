<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VATUSA - @yield('title')</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-formhelpers.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/vatusa.css" rel="stylesheet">
    <link href="/css/vatusaapp.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/Footer-white.css">
    <link rel="stylesheet" href="/js/vatusaapp.js">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

 
</head>
<body>
<div class="container-head">

    <div class="head">
   
        <div class="layer">

            <div class="container">
            <img src="/photos/logo_white.png" class="logo"/>

@if(Auth::check())

    <div class="pull-right">

        <div class="wb-usr">

            <span class="grab"><strong>{{Auth::user()->fname.' '.Auth::user()->lname}}</strong></span>

            <br>

            <small><i class="fa fa-user"></i> {{Auth::user()->id}} &nbsp; &mdash; &nbsp; <i

                        class="fa fa-trophy"></i> 

                {{Auth::user()->RatingLong}}<br>

            </small>

        </div>

    </div>

@endif
               

              
            </div>

        </div>

    </div>

    <div class="clear-fix"></div>

    <nav class="navbar navbar-default" id="nav">

        <div class="container">

            <div class="navbar-header">



            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"

aria-expanded="false" aria-controls="navbar">

<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span

    class="icon-bar"></span> <span class="icon-bar"></span></button>

</div>

<div id="navbar" class="navbar-collapse collapse">

<ul class="nav navbar-nav">

<li><a href="/">

    Home

</a></li>

<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"

                    aria-expanded="false">

    Pilots

    <span class="caret"></span></a>

<ul class="dropdown-menu" role="menu">
    <li><a href="https://www.aviationapi.com/charts" target="_blank">Charts</a></li>
    <li><a href="http://www.vatstar.com/" target="_blank">VATSTAR Training</a></li>
    <li><a href="https://www.aviationweather.gov/" target="_blank">Weather</a></li>
    <li><a href="/pilots/scenery">Scenery</a></li>
    <li><a href="/pilots/airports" target="_blank">Airports</a></li>
    <li><a href="/pilots/request-staffing">Request Staffing</a></li>
    <li><a href="http://www.flightaware.com/statistics/ifr-route/" target="_blank">IFR Routes</a></li>

</ul>

</li>

                   

                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"

                                            aria-expanded="false">

                            Controllers <span class="caret"></span>

                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/controllers/roster">Roster</a></li>
                            <li><a href="/controllers/staff" target="_blank">Staff</a></li>
                            <li><a href="/contorllers/files">Files</a></li>
                            <li><a href="/contorllers/stats">Controller Stats</a></li>
                            <li><a href="/contorllers/teamspeak">Teamspeak Info</a></li>
                        </ul>

                    </li>
                    @if(Auth::guest())
                    <li><a href="/visit">Vist ZTL</a></li>
                    <li><a href="/feedback/new">Feedback</a></li>
                @endif
                
                   
</ul>       
                    <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"

aria-expanded="false">
@if(Auth::check())
<i class="fa fa-user"></i>My Profile<span class="caret"></span></a>
<ul class="dropdown-menu" role="menu">
<li class="dropdown-header"><h5 style="font-weight: bold; margin-top: 5px; margin-bottom: 5px;">Membership</h5></li>
<li><a href="/dashboard/controllers/profile">My Profile</a></li>
<li class="nav-divider"></li>
<li class="dropdown-header"><h5 style="font-weight: bold; margin-top: 5px; margin-bottom: 5px;">Training</h5></li>
<li><a class="nav-link {{ Nav::urlDoesContain('/dashboard/training/tickets/mentor/mentoravi') }}" href="/dashboard/controllers/mentoravi">Schedule a Training Session</a></li>
<li><a class="nav-link {{ Nav::urlDoesContain('dashboard/training/info') }}" href="/dashboard/training/info">Training Information</a></li>
<li><a class="nav-link {{ Nav::urlDoesContain('/dashboard/training/atcast') }}" href="/dashboard/training/atcast">ATCast Videos</a></li>
<li class="nav-divider"></li>
<li class="dropdown-header"><h5 style="font-weight: bold; margin-top: 5px; margin-bottom: 5px;">Feedback</h5></li>
<li><a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/incident/report') }}" href="/dashboard/controllers/incident/report">Incident Report</a></li>
<li class="nav-divider"></li>
<li><a class="nav-link" href="/logout" target="_blank">Logout</a></li>
</ul>
@else
<li><a class="fa fa-user" href ="/login">Controller Login</a></li>

@endif                    

@if(Auth::user()->can('staff') || Auth::user()->can('email') || Auth::user()->can('scenery') || Auth::user()->can('files') || Auth::user()->can('events'))
<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"

                                            aria-expanded="false">

                            Actions <span class="caret"></span>

                        </a>

                        <ul class="dropdown-menu" role="menu">
                        @if(Auth::user()->can('snrStaff'))
                        <li class="dropdown-header"><h5 style="font-weight: bold; margin-top: 5px; margin-bottom: 5px;">Membership</h5></li>
                            <li><a href="/dashboard/controllers/roster">Roster Management</a></li>
                            <li><a href="/controllers/staff" target="_blank">Feedback Management</a></li>
                            <li><a href="/contorllers/files">Incident Report Management</a></li>
                            <li><a href="/contorllers/files">Bronze MIC Management</a></li>
                            <li><a href="/contorllers/files">Website Activity</a></li>
                        @endif
                            <li class="nav-divider"></li>
                        @if(Auth::user()->can('staff'))
                            <li class="dropdown-header"><h5 style="font-weight: bold; margin-top: 0; margin-bottom: 5px;">Announcements/ Airports</h5></li>
                            <li><a href="/dashboard/controller/roster">Calendar/News Management</a></li>
                            <li><a href="/controllers/staff" target="_blank">Airport Management Management</a></li>
                            @if(Auth::user()->can('scenery'))
                                <li><a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/scenery') }}" href="/dashboard/admin/scenery">Scenery Management</a></li>
                            @endif
                        @endif
                        <li class="nav-divider"></li>
                        @if(Auth::user()->can('email'))
                            <li class="dropdown-header"><h5 style="font-weight: bold; margin-top: 0; margin-bottom: 5px;">Email</h5></li>
                            <li><a href="/dashboard/admin/email/send">Send Email/Broadcast</a></li>
                        @endif
        
                        </ul>

                    </li>
@endif                          
@if(Auth::user()->can('train'))
<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"

                                            aria-expanded="false">

                            INS/Mentors <span class="caret"></span>

                        </a>
                        
                        <ul class="dropdown-menu" role="menu">                   
                        <li><a href="/dashboard/training/tickets">Training Tickets</a></li>
                        <li><a href="/dashboard/admin/roster">Update Certs</a></li>
                        <li><a href="/dashboard/roster">Schedule Maanagement</a></li>
                        @if(Auth::user()->hasRole('ins') || Auth::user()->can('snrStaff'))
                            <li><a class="nav-link {{ Nav::urlDoesContain('dashboard/training/ots-center') }}" href="/dashboard/training/ots-center">OTS Center</a></li>
                        @endif
                        
@endif
                  
                

</li>





                           </div>
                           </nav>
<div>
                        


@include('inc.messages')
@yield('content')

<footer>

    <div class="container">

        <hr>

        <p>Copyright &copy; 2016-{{ date("Y") }} VATUSA - United States Division, VATSIM. All

            rights reserved. Any and all content on this website are for use with the Virtual Air Traffic Simulation

            Network (VATSIM) and may not be used for real-world navigation or aviation purposes and doing so could be a

            violation of federal law.</p>

       

    </div>

</footer>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>

<script src="/js/bootbox.min.js"></script>

<script src="/js/jquery-ui.min.js"></script>

<script src="/js/vatusa.js"></script>

<script src="/js/bootstrap-formhelpers.js"></script>
</body>
</html>