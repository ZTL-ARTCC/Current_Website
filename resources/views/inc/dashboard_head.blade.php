    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">
            @include('inc.logo', ['color' => 'black'])
        </a>
        <div class="d-flex justify-content-start ms-5 collapse navbar-collapse">

                {{ html()->form()->route('searchAirport')->class(['row','row-cols-lg-auto'])->open() }}
                    <div class="col-12 input-group">
                        {{ html()->text('apt', null)->placeholder('Search Airport ICAO')->class(['form-control']) }}
                        &nbsp;
                        <button class="btn btn-success" type="submit">Search</button>
                    </div>
                {{ html()->form()->close() }}
</div>

            <ul class="navbar-nav ms-auto">
			<a class="nav-link {{ Nav::isRoute('controller_dash_home') }}" href="/dashboard">Dashboard Home</a>
                <li class="nav-item dropdown">
                    <a class="nav-link" style="pointer-events:none">{{ Auth::user()->full_name }} - {{ Auth::user()->rating_short }}</a>
                </li>
            </ul>
        </div>
</div>
    </nav>

