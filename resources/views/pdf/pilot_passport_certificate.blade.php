<html>
    <head>
        <link rel="stylesheet" href="{{public_path('css/certificate.css')}}">
        <style>
            body {
                background-image: url('{{public_path('/photos/pilot_passport/certificate_background.jpg')}}');
                background-color: #cccccc;
                background-position: center; 
                background-repeat: no-repeat; 
                background-size: cover; 
            }
        </style>
    </head>
    <body>
        <div class="certificate-text">
            <h4>In Recognition</h4>
            <h2>{{ $pilot_name }}</h2>
            <h4>Has Hereby Completed the {{ $phase_title }} Challenge</h4>
            <h6>of the</h6>
            <h4>ZTL Pilot Passport Program</h4>
            <h6>On this {{ $award_date->format('jS') }} day of {{ $award_date->month }}, {{ $award_date->year }}</h6>
        </div>
        <div class="medal-block">
            <img src="{{public_path('/photos/pilot_passport/challenge_medal.png')}}" height="300px">
        </div>
        <div class="signature-block">
            <span class="signature-field">{{ $atm_name }}</span>
            <span class="identification-block">
                {{ $atm_name }}
                <br>
                Air Traffic Manager
                <br>
                Atlanta Virtual ARTCC
            </span>
        </div>
    </body>
</html>
