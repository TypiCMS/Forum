@extends('core::admin.master')

@section('title', __('New category'))

@section('content')

    {!! BootForm::open()->action(route('admin::index-forum-categories'))->multipart()->role('form') !!}
    @include('forum::admin._form-category')
    {!! BootForm::close() !!}

@endsection
