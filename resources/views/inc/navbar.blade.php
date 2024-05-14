<div class="bg-dark w-100">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="/dashboard">
            @include('inc.logo', ['color' => 'white'])
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
                            <a class="dropdown-item" href="/controllers/teamspeak">TeamSpeak Information</a>
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
