@extends('core::admin.master')

@section('title', $model->present()->title)

@section('content')
    {!! BootForm::open()->put()->action(route('admin::update-forum-category', $model->id))->multipart()->role('form') !!}
    {!! BootForm::bind($model) !!}
    @include('forum::admin._form-category')
    {!! BootForm::close() !!}
@endsection
