@php ($header_type = (isset($type)) ? $type : 'internal')
@if($header_type == 'external')
<span class="border border-light py-4 mb-4 view-header">
    <div class="container">
        @isset($content)
            {!! $content !!}
        @endisset
        <h2>{!! $title !!}</h2>
    </div>
</span>
@else
<div class="container-fluid py-4 mb-4 view-header">
    <h2 class="mb-0">{!! $title !!}</h2>
</div>
@endif
