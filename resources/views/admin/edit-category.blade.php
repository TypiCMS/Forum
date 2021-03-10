@extends('core::admin.master')

@section('title', $model->present()->title)

@section('content')

    <div class="header">
        @include('core::admin._button-back', ['module' => 'categories', 'url' => route('admin::index-forum-categories')])
        <h1 class="header-title @empty($model->title)text-muted @endempty">
            {{ $model->present()->title ?: __('Untitled') }}
        </h1>
    </div>

    {!! BootForm::open()->put()->action(route('admin::update-forum-category', $model->id))->multipart()->role('form') !!}
    {!! BootForm::bind($model) !!}
        @include('forum::admin._form-category')
    {!! BootForm::close() !!}

@endsection
