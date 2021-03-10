@extends('core::admin.master')

@section('title', __('New category'))

@section('content')

    <div class="header">
        @include('core::admin._button-back', ['module' => 'categories', 'url' => route('admin::index-forum-categories')])
        <h1 class="header-title">@lang('New category')</h1>
    </div>

    {!! BootForm::open()->action(route('admin::index-forum-categories'))->multipart()->role('form') !!}
        @include('forum::admin._form-category')
    {!! BootForm::close() !!}

@endsection
