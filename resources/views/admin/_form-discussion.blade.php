@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}

<div class="row gx-3">
    <div class="col-md-6">
        {!! BootForm::text(__('Title'), 'title') !!}
    </div>
    <div class="col-md-6">
        <div class="mb-3 @if ($errors->has('slug'))has-error @endif">
            {!! Form::label('<span>'.__('Slug').'</span>')->addClass('form-label')->forId('slug') !!}
            <div class="input-group">
                {!! Form::text('slug')->addClass('form-control')->addClass($errors->has('slug') ? 'is-invalid' : '')->id('slug')->data('slug', 'title') !!}
                <button class="btn btn-outline-dark btn-slug" type="button">{{ __('Generate') }}</button>
                {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
</div>

{!! BootForm::text(__('Color'), 'color')->type('color') !!}
