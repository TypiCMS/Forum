@if ($errors->count() > 0)
<div class="alert alert-danger alert-dismissible fade show">
    <div class="container position-relative">
        <p><strong>@lang('Oh Snap!')</strong> @lang('Please fix the following errors:')</p>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif
