@if(count($errors) > 0)
    <br>
    <div class="alert alert-danger">
        <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

@if(session()->has($SessionVariables::SUCCESS->value))
    <br>
    <div class="alert alert-success">
        {{ session($SessionVariables::SUCCESS->value) }}
    </div>
@endif

@if(session()->has($SessionVariables::ERROR->value))
    <br>
    <div class="alert alert-danger">
        {{ session($SessionVariables::ERROR->value) }}
    </div>
@endif
