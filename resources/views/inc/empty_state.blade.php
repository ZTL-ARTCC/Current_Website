<div class="card mb-3">
    <div class="card-body text-center">
        <h4>{!! $header !!}</h4>
        <p>{!! $body !!}</p>
        @if(isset($icon))
            <h4 class="{!! $icon !!}"></h4>
        @endif
    </div>
</div>
