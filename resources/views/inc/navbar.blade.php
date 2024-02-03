@push('custom_header')
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}" />
@endpush

<div class="bg-dark w-100">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="/dashboard">
            @toggle("custom_theme_logo")
                @php
                    $month = Carbon\Carbon::now()->month;
                    $day = Carbon\Carbon::now()->day;
                @endphp

                @if(($month == 12 && $day == 31) || ($month == 1 && $day == 1))
                    <img src="/photos/logos/ztl_logo_fireworks-white.png">
                @elseif(($month >= 3 && $day >= 20) && $month <= 4)
                    <img src="/photos/logos/ztl_logo_spring-white.png">
                @elseif(($month == 1 && $day == 16) || ($month == 2 && $day == 20) || ($month == 5 && $day == 29) || ($month == 6 && $day == 19) || ($month == 7 && $day == 4) || ($month == 9 && $day == 4) || ($month == 10 && $day == 10))
                    <img src="/photos/logos/ztl_logo_flag-white.png">
                @elseif($month >= 6 && $month <= 9)
                    <img src="/photos/logos/ztl__logo_beach-white.png">
                @elseif($month == 10)
                    <img src="/photos/logos/ztl_logo_halloween-white.png">
                @elseif($month == 11 && $day >= 20)
                    <img src="/photos/logos/ztl_logo_turkey-white.png">
                @elseif($month == 12)
                    <img src="/photos/logos/ztl_logo_santa-white.png">
                @else
                    <img src="/photos/logos/0_ztl_logo_white.png">
                @endif
            @else
                <img src="/photos/logos/0_ztl_logo_white.png">
            @endtoggle
        </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExample10">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    @toggle('realops')
                        <li class="nav-item">
                            <a class="nav-link" href="/realops">Realops</a>
                        </li>
                    @endtoggle
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="pilots" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pilots</a>
                        <div class="dropdown-menu" aria-labelledby="pilots">
                            <a class="dropdown-item" href="https://www.aviationapi.com/charts" target="_blank">Charts</a>
                            <a class="dropdown-item" href="https://www.aviationweather.gov/" target="_blank">Weather</a>
                            <a class="dropdown-item" href="/pilots/scenery">Scenery</a>
                            <a class="dropdown-item" href="/pilots/airports">Airports</a>
                            <a class="dropdown-item" href="/pilots/request-staffing">Request Staffing</a>
                            <a class="dropdown-item" href="http://www.flightaware.com/statistics/ifr-route/" target="_blank">IFR Routes</a>
                            <a class="dropdown-item" href="/pilots/guide/atl">ATL Pilot Guide</a>
                            <a class="dropdown-item" href="/ramp-status/atl">ATL Gate Status</a>
                            <a class="dropdown-item" href="/ramp-status/clt">CLT Gate Status</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="controllers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Controllers</a>
                        <div class="dropdown-menu" aria-labelledby="controllers">
                            <a class="dropdown-item" href="/controllers/roster">Roster</a>
                            <a class="dropdown-item" href="/controllers/staff">Staff</a>
                            <a class="dropdown-item" href="/controllers/files">Files</a>
                            <a class="dropdown-item" href="https://ids.ztlartcc.org">vIDS</a>
                            <a class="dropdown-item" href="/controllers/stats">Controller Stats</a>
                            <a class="dropdown-item" href="/controllers/teamspeak">Teamspeak Information</a>
                        </div>
                    </li>
                    @if(Auth::guest())
                        <a class="nav-link" href="{{ URL::to('/'); }}/visit">Visit ZTL</a>
                        <a class="nav-link" href="/feedback/new">Feedback</a>
                    @endif
                    @if(Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dashboard" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->full_name }} - {{ Auth::user()->rating_short }}</a>
                            <div class="dropdown-menu" aria-labelledby="dashboard">
                                <a class="dropdown-item" href="/dashboard/controllers/profile"><i class="fas fa-user"></i> My Profile</a>
                                <a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt"></i> Controller Dashboard</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Controller Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
