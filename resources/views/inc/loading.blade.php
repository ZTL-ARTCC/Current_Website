@vite('resources/assets/sass/loading.scss')

@php
$hidden = (isset($hidden)) ? $hidden : true;
@endphp

<!-- Loading Overlay -->
<div id="loading" class="loading-overlay {!! $hidden ? 'd-none' : '' !!}">
    <div class="card shadow-lg border-0 loading-card">
        <div class="card-body text-center">
            <div class="spinner-border text-primary mb-3">
                <span class="visually-hidden">Loading...</span>
            </div>

            <h4 class="mb-2">
                Loading...
            </h4>

            <p class="card-text text-muted mb-3">
                {!! $message !!}
            </p>

            <h4 class="fa-solid fa-hourglass"></h4> 
        </div>
    </div>
</div>

