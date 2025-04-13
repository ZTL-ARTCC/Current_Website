<!doctype html>
<html lang="ENG">
    <head>
        {{-- Meta Stuff --}}
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="vZTL ARTCC Website. For entertainment purposes only. Do not use for real world purposes. Part of the VATSIM Network.">
        <meta name="keywords" content="ztl,vatusa,vatsim,atlanta,center,georgia,artcc,aviation,airplane,airport,charlotte,controller,atc,air,traffic,control,pilot">
        <meta name="author" content="ZTL Web Team">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Stylesheets --}}
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/footer_white.css') }}">
	
        @if(Carbon\Carbon::now()->month == 12)
            {{-- Merry Christmas --}}
            <script src="/js/snowstorm.js"></script>
        @endif

        {{-- Bootstrap --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        {{-- Custom JS --}}
        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>

        {{-- Bootstrap Date/Time Picker --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

        {{-- Google reCAPTCHA v2 --}}
        <script src='https://www.google.com/recaptcha/api.js'></script>

        {{-- Fontawesome --}}
        <script src="https://kit.fontawesome.com/f3eeeb43e3.js" crossorigin="anonymous"></script>

        {{-- Custom Headers --}}
            @stack('custom_header')
            
        {{-- Title --}}
        <title>
            @yield('title') | ZTL ARTCC
        </title>
    </head>
    <body>

        {{-- Messages --}}
        @include('inc.home_messages')

        {{-- Navbar --}}
        @include('inc.navbar')

        {{-- Content --}}
        @yield('content')

        {{-- Footer --}}
        @include('inc.home_footer')
    </body>
</html>
