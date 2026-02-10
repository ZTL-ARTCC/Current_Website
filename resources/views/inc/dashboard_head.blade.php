<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">
            @include('inc.logo', ['color' => 'black'])
        </a>
        <ul class="navbar-nav me-auto align-items-center">
            {{ html()->form()->route('searchAirport')->class(['form-inline'])->open() }}
                <div class="col-12 input-group">
                    {{ html()->text('apt', null)->placeholder('Search Airport ICAO')->class(['form-control']) }}
                    &nbsp;
                    <button class="btn btn-success" type="submit">Search</button>
                </div>
            {{ html()->form()->close() }}
        </ul>
        <ul class="navbar-nav ml-auto align-items-center">
            <a class="nav-link {{ Nav::isRoute('controller_dash_home') }}" href="/dashboard">Dashboard Home</a>
            @if(toggleEnabled('impersonation') && $is_impersonating)
                <a class="nav-link" href="/dashboard/admin/impersonation/stop">End Impersonation</a>
            @endif
            @if(toggleEnabled('impersonation') && Auth::user()->isAbleTo('snrStaff'))
                {{ html()->form()->route('startImpersonation')->class(['form-inline'])->open() }}
                    {{ html()->select('user_id', $users, Auth::id())->class(['form-select'])->attributes(['onchange' => 'this.form.submit()'])->disabled($is_impersonating) }}
                {{ html()->form()->close() }}
            @else
                <a class="nav-link disabled">{{ Auth::user()->full_name }} - {{ Auth::user()->rating_short }}</a>
            @endif
        </ul>
    </div>
</nav>

