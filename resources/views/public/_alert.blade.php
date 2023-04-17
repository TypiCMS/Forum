@if (Session::has('forum_alert'))
    <div class="alert alert-{{ Session::get('forum_alert_type') }} alert-dismissible fade show" role="alert">
        {{ Session::get('forum_alert') }}
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="@lang('Close')"></button>
    </div>
@endif
