<div class="container mb-2">
    @isset($error_message)
    <div class="card rounded alert alert-warning">
        <div class="card-header">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>Error!
        </div>
        <div class="card-body">
            {{ $error_message }}
        </div>
    </div>
    @endif
    @if($checklist == null)
    <div class="form-group">
        <div class="row">
            <div class="col-sm-4">
                <label for="cid">Enter your CID</label>
                <input type="text" class="form-control" placeholder="Required" wire:model="cid">
                @error('cid')<div class="text-danger text-sm">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-4">
                <button class="btn btn-success" wire:click="checkCID" wire:loading.attr="disabled"><i class="fa-solid fa-magnifying-glass me-2"></i>Check CID</button>
                <div wire:loading><img src="{{ Vite::image('180-ring-with-bg-black-36.svg') }}" class="ms-2" alt="loading"></div>
            </div>
        </div>
    </div>
    @endif

    @isset($checklist)
    <div class="card rounded alert alert-primary">
        <div class="card-body">
            <p>Dear Prospective Controller,</p>
            <p style="text-indent: 2em;">We are thrilled that you are interested in joining 
            the {{ config('artcc.id') }} team. 
            @if($checklist->visiting)
            Please fill out the form below and briefly explain your motivation for
            applying as a visiting controller. After submitting the form, you will receive an automated
            email to {{ $email_redacted }} with information on next steps.
            @else
            You do not meet the visiting controller criteria at this time. See <a href="https://vatusa-storage.nyc3.cdn.digitaloceanspaces.com/docs/general-division-policy.pdf" target="_blank" alt="DP001">DP001 - VATUSA General Division Policy</a> for more information.
            @endif
            </p>
            <h5>Visiting controller eligibility criteria:</h5>
            <ul>
                <li>You have a home facility {!! $this->checklistIcon('hasHome') !!}</li>
                <li>You have earned at least an S3 rating {!! $this->checklistIcon('hasRating') !!}</li>
                <li>It has been at least 60 days since you joined a visiting roster {!! $this->checklistIcon('60days') !!}</li>
                <li>It has been at least 90 days since promotion to S1, S2, S3, or C1 {!! $this->checklistIcon('90days') !!}</li>
                <li>You have controlled 50 hours since promotion to S1, S2, S3, or C1 {!! $this->checklistIcon('50hrs') !!}</li>         
            </ul>
            <p>Best wishes and we hope to see you on the scopes soon!</p>
            <p>{{ $atm_name }}<br>Air Traffic Manager</p>
        </div>
    </div>
    @if($checklist->visiting)
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" wire:model="acknowledge">
        <label class="form-check-label" for="acknowledge">
            I read and acknowledge that I meet the requirements as stated above.
        </label>
        @error('acknowledge')<div class="text-danger text-sm">{{ $message }}</div>@enderror
    </div>            
    <div class="form-group">
        <label for="reason">Explain why you want to visit the {{ config('artcc.id') }} ARTCC</label>
        <textarea class="form-control" rows="4" placeholder="Required" wire:model="justification"></textarea>
        @error('justification')<div class="text-danger text-sm">{{ $message }}</div>@enderror
    </div>
    <div wire:ignore 
         x-data="{
             init() {
                 grecaptcha.render($el, {
                     'sitekey': '{{ config('google.site_key') }}',
                     'callback': (token) => {
                         @this.set('recaptchaToken', token);
                     },
                     'expired-callback': () => {
                         @this.set('recaptchaToken', null);
                     }
                 });
             }
         }">
    </div>
    @error('recaptchaToken')<div class="text-danger text-sm">{{ $message }}</div>@enderror
    <br>
    <button class="btn btn-success" wire:click="submitVisitRequest" wire:loading.attr="disabled"><i class="fa-regular fa-hand-point-right me-2"></i>Submit Visit Request</button>
    @endif
    @endisset
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</div>
