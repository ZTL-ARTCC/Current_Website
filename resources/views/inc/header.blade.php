@if($type == 'external')
<span class="border border-light py-4 mb-4 view-header">
    <div class="container">
        @if($content)
            {{ $content }}
        @endif
        <h2>{{ $title }}</h2>
    </div>
</span>
@else
<div class="container-fluid py-4 mb-4 view-header">
    <h2>{{ $title }}</h2>
</div>
@endif
