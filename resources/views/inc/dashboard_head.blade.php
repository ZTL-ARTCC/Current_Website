<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="/dashboard">
            @include('inc.logo', ['color' => 'black'])
        </a>
            <ul class="navbar-nav">
                {{ html()->form()->route('searchAirport')->open() }}
                    <div class="form-inline">
                        {{ html()->text('apt', null)->placeholder('Search Airport ICAO')->class(['form-control']) }}
                        &nbsp;
                        <button class="btn btn-success" type="submit">Search</button>
                    </div>
                {{ html()->form()->close() }}
            </ul>
            &nbsp;&nbsp;&nbsp;

            <ul class="navbar-nav ml-auto">
			<a class="nav-link {{ Nav::isRoute('controller_dash_home') }}" href="/dashboard">Dashboard Home</a>
                <li class="nav-item dropdown">
                    <a class="nav-link" style="pointer-events:none">{{ Auth::user()->full_name }} - {{ Auth::user()->rating_short }}</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
