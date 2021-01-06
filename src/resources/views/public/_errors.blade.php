@if ($errors->count() > 0)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <p><strong>@lang('Oh Snap!')</strong> @lang('Please fix the following errors:')</p>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="@lang('Close')"></button>
</div>
@endif
