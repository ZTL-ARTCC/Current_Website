@php
    $logo_id = 'ztl_logo_';
    $logo_bg_color = 'white';
    if(isset($color)) {
        if(in_array($color, ['white','black'])) {
            $logo_bg_color = $color;
        }
    }
@endphp
@toggle('custom_theme_logo')
    @php
        $month = Carbon\Carbon::now()->month;
        $day = Carbon\Carbon::now()->day;
    @endphp
    @if(($month == 12 && $day == 31) || ($month == 1 && $day == 1))
        @php ($logo_id = 'ztl_logo_fireworks-')
    @elseif(($month >= 3 && $day >= 20) && $month <= 4) 
        @php ($logo_id = 'ztl_logo_spring-')
    @elseif(($month == 1 && $day == 16) || ($month == 2 && $day == 20) || ($month == 5 && $day == 29) || ($month == 6 && $day == 19) || ($month == 7 && $day == 4) || ($month == 9 && $day == 4) || ($month == 10 && $day == 10))
        @php ($logo_id = 'ztl_logo_flag-')
    @elseif($month >= 6 && $month <= 9)
        @php ($logo_id = 'ztl_logo_beach-')
    @elseif($month == 10)
        @php ($logo_id = 'ztl_logo_halloween-')
    @elseif($month == 11 && $day >= 20)
        @php ($logo_id = 'ztl_logo_turkey-')
    @elseif($month == 12)
        @php ($logo_id = 'ztl_logo_santa-b')
    @endif
@endtoggle
<img src="/photos/logos/{{ $logo_id }}{{ $logo_bg_color }}.png">