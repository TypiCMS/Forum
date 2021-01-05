@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}

<div class="row gx-3">
    <div class="col-md-6">
        {!! TranslatableBootForm::text(__('Name'), 'name') !!}
    </div>
    <div class="col-md-6">
        @foreach ($locales as $lang)
        <div class="mb-3 form-group-translation @if ($errors->has('slug.'.$lang))has-error @endif">
            {!! Form::label('<span>'.__('Slug').'</span> <span>('.$lang.')</span>')->addClass('control-label')->forId('slug['.$lang.']') !!}
            <span></span>
            <div class="input-group">
                {!! Form::text('slug['.$lang.']')->addClass('form-control')->addClass($errors->has('slug.'.$lang) ? 'is-invalid' : '')->id('slug['.$lang.']')->data('slug', 'name['.$lang.']')->data('language', $lang) !!}
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary btn-slug" type="button">{{ __('Generate') }}</button>
                </span>
                {!! $errors->first('slug.'.$lang, '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        @endforeach
    </div>
</div>

{!! BootForm::text(__('Color'), 'color')->type('color') !!}
