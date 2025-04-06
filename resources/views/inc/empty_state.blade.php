@php
$header_style = (isset($header_class)) ? $header_class : '';
$body_style = (isset($body_class)) ? $body_class : '';
@endphp
<div class="card mb-3">
    <div class="card-body text-center">
        <h4 class="{{ $header_style }}">{!! $header !!}</h4>
        <p class="{{ $body_style }}">{!! $body !!}</p>
        @if(isset($icon))
            <h4 class="{!! $icon !!} {{ $body_style }}"></h4>
        @endif
    </div>
</div>
