<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="/dashboard">
            @toggle("custom_theme_logo")
                @php
                    $month = Carbon\Carbon::now()->month;
                    $day = Carbon\Carbon::now()->day;
                @endphp

                @if(($month == 12 && $day == 31) || ($month == 1 && $day == 1))
                    <img width="100" src="/photos/logos/ztl_logo_fireworks-black.png">
                @elseif(($month >= 3 && $day >= 20) && $month <= 4)
                    <img width="100" src="/photos/logos/ztl_logo_spring-black.png">
                @elseif(($month == 1 && $day == 16) || ($month == 2 && $day == 20) || ($month == 5 && $day == 29) || ($month == 6 && $day == 19) || ($month == 7 && $day == 4) || ($month == 9 && $day == 4) || ($month == 10 && $day == 10))
                    <img width="100" src="/photos/logos/ztl_logo_flag-black.png">
                @elseif($month >= 6 && $month <= 9)
                    <img width="100" src="/photos/logos/ztl__logo_beach-black.png">
                @elseif($month == 10)
                    <img width="100" src="/photos/logos/ztl_logo_halloween-black.png">
                @elseif($month == 11 && $day >= 20)
                    <img width="100" src="/photos/logos/ztl_logo_turkey-black.png">
                @elseif($month == 12)
                    <img width="100" src="/photos/logos/ztl_logo_santa-black.png">
                @else
                    <img width="100" src="/photos/logos/0_ztl_logo_black.png">
                @endif
            @else
                <img width="100" src="/photos/logos/0_ztl_logo_black.png">
            @endtoggle
        </a>
            <ul class="navbar-nav">
                {!! Form::open(['action' => 'ControllerDash@searchAirport']) !!}
                    <div class="form-inline">
                        {!! Form::text('apt', null, ['placeholder' => 'Search Airport ICAO', 'class' => 'form-control']) !!}
                        &nbsp;
                        <button class="btn btn-success" type="submit">Search</button>
                    </div>
                {!! Form::close() !!}
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



<style>
.switch {
  position: relative;
  display: inline-block;
  width: 45px;
  height: 26px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: gray;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: lightgreen;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}


/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
