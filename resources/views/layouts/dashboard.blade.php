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
        <link rel="stylesheet" href="{{ mix('/css/dashboard.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/footer_white.css') }}">

        {{-- jQuery --}}
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

        {{-- Custom JS --}}
        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>

        {{-- Custom Headers --}}
        @stack('custom_header')

        {{-- Sidebar Menu Styles --}}
        <link rel="stylesheet" href="{{ mix('/css/sidebar.css') }}">

        {{-- Title --}}
        <title>
            @yield('title') | ZTL ARTCC
        </title>
    </head>
    <body>
        {{-- Messages --}}
        @include('inc.messages')

        {{-- Navbar --}}
        @include('inc.dashboard_head')

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2">
                    {{-- Sidebar --}}
                    @include('inc.sidebar')
                </div>
                <div class="col-sm-10">
                    {{-- Content --}}
                    @yield('content')
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('inc.footer')
    </body>
</html>
