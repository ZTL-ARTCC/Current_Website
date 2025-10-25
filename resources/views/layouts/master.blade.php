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
        @vite('resources/assets/sass/app.scss')
        @vite('resources/assets/sass/footer_white.scss')
	
        @if(Carbon\Carbon::now()->month == 12)
            {{-- Merry Christmas --}}
            @vite('resources/assets/js/snowstorm.jsx')
        @endif

        {{-- Custom JS --}}
        @vite('resources/assets/js/app.js')

        {{-- Google reCAPTCHA v2 --}}
        <script src='https://www.google.com/recaptcha/api.js'></script>

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
