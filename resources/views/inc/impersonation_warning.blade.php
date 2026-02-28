@if($is_impersonating && toggleEnabled($FeatureToggles::IMPERSONATION))
    <br>
    <a class="d-block alert alert-warning" href="/dashboard/admin/impersonation/stop">
        WARNING: You are currently impersonating a user. Use extreme caution as any action you perform will be performed at that user, and will be tracked as so with you as the impersonator. This should be used for debugging and development only! Click this warning at any time to end impersonation.
    </a>
@endif
