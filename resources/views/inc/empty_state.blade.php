@php
$text_style = '';
if(isset($color)) {
    switch($color) {
        case 'red' : $text_style = 'text-danger';
        break;
        case 'yellow' : $text_style = 'text-warning';
        break;
        case 'green' : $text_style = 'text-success';
        break;
        case 'blue' : $text_style = 'text-primary';
        break;
    }
}
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
