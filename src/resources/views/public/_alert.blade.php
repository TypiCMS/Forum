@if (Session::has('forum_alert'))
<div class="alert alert-{{ Session::get('forum_alert_type') }} alert-dismissible fade show" role="alert">
    <div class="container position-relative">
        {{ Session::get('forum_alert') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
