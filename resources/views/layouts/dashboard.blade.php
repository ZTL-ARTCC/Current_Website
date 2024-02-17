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
        <link rel="stylesheet" href="/css/app.css">
        <link rel="stylesheet" href="/css/dashboard.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/footer_white.css">

        {{-- Bootstrap --}}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

        {{-- Custom JS --}}
        <script type="text/javascript" src="/js/app.js"></script>

        {{-- Bootstrap Date/Time Picker --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

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
        @include('inc.messages')

        {{-- Navbar --}}
        @include('inc.dashboard-head')

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
