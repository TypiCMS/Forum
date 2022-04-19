<div class="header">
    @include('core::admin._button-back', ['url' => $model->indexUrl(), 'title' => __('Categories')])
    @include('core::admin._title', ['default' => __('New category')])
    @component('core::admin._buttons-form', ['model' => $model])
    @endcomponent
</div>

<div class="content">

    {!! BootForm::hidden('id') !!}

    <div class="row gx-3">
        <div class="col-md-6">
            {!! TranslatableBootForm::text(__('Name'), 'name') !!}
        </div>
        <div class="col-md-6">
            @foreach ($locales as $lang)
            <div class="mb-3 form-group-translation @if ($errors->has('slug.'.$lang))has-error @endif">
                {!! Form::label('<span>'.__('Slug').'</span> <span>('.$lang.')</span>')->addClass('form-label')->forId('slug['.$lang.']') !!}
                <span></span>
                <div class="input-group">
                    {!! Form::text('slug['.$lang.']')->addClass('form-control')->addClass($errors->has('slug.'.$lang) ? 'is-invalid' : '')->id('slug['.$lang.']')->data('slug', 'name['.$lang.']')->data('language', $lang) !!}
                    <button class="btn btn-outline-dark btn-slug" type="button">{{ __('Generate') }}</button>
                    {!! $errors->first('slug.'.$lang, '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {!! BootForm::text(__('Color'), 'color')->type('color') !!}

</div>
