@extends('layouts.master')

@section('title')
    Redirecting to Moodle...
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Redirecting you to Moodle...</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <h3>Wait hold short just a moment longer! We're redirecting you to Moodle. If you aren't automatically redirected within 10 seconds, please click the button below.</h3>
                        <br>
                        <form action="https://moodle.ztlartcc.org/login/index.php" method="post" id="moodleLogin">
                            <input type="hidden" name="username" value="{{ Auth::id() }}" />
                            <input type="hidden" name="password" value="{{ Auth::user()->getMoodlePassword() }}" />
                            <button class="btn btn-success" type="submit">Access Moodle</button>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <img src="/photos/wait.gif" width="300px">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // We want it to automatically submit/redirect
        document.getElementById('moodleLogin').submit();
    </script>

@endsection
