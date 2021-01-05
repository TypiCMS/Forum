@if (Session::has('forum_alert'))
<div class="alert alert-{{ Session::get('forum_alert_type') }} alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::get('forum_alert') }}
</div>
@endif
