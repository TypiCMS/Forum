@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

<div class="row">
    <div class="col-md-6">
        {!! BootForm::text(__('Name'), 'name') !!}
    </div>
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('slug'))has-error @endif">
            {!! Form::label('<span>'.__('Slug').'</span>')->addClass('control-label')->forId('slug') !!}
            <div class="input-group">
                {!! Form::text('slug')->addClass('form-control')->addClass($errors->has('slug') ? 'is-invalid' : '')->id('slug')->data('slug', 'name') !!}
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary btn-slug" type="button">{{ __('Generate') }}</button>
                </span>
                {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
</div>

{!! BootForm::text(__('Color'), 'color')->type('color') !!}
