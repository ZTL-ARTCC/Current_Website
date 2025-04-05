@php
$text_style = (isset($class)) ? $class : '';
@endphp
<div class="card mb-3">
    <div class="card-body text-center">
        <h4>{!! $header !!}</h4>
        <p class="{{ $text_style }}">{!! $body !!}</p>
        @if(isset($icon))
            <h4 class="{!! $icon !!} {{ $text_style }}"></h4>
        @endif
    </div>
</div>
