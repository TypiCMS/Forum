@extends('core::admin.master')

@section('title', $model->present()->title)

@section('content')

<div class="header">
    @include('core::admin._button-back', ['url' => $model->indexUrl(), 'title' => __('Discussions')])
    @include('core::admin._title', ['default' => __('New discussion')])
</div>

<div class="content">
    <table class="table table-sm">
        <tbody>
            <tr>
                <th>@lang('Title')</th>
                <td>{{ $model->title }}</td>
            </tr>
            <tr>
                <th>@lang('Category')</th>
                <td>{{ $model->category->name }}</td>
            </tr>
            <tr>
                <th>@lang('Views')</th>
                <td>{{ $model->views }}</td>
            </tr>
        </tbody>
    </table>
    <h2 class="mb-4">@lang('Posts')</h2>
    @if ($model->postsWithTrashed->count() > 0)
    <ul class="list-unstyled">
        @foreach ($model->postsWithTrashed as $post)
        <li class="card mb-4 @if ($post->deleted_at !== null) text-muted @endif">
            <div class="card-header">
                <strong>{{ $post->user->first_name }} {{ $post->user->last_name }}</strong> <small class="text-muted">{{ $post->created_at }}</small>
                @empty(!$post->deleted_at) <small class="text-danger">@lang('deleted')</small> @endempty
            </div>
            <div class="card-body">
                {!! $post->body !!}
            </div>
        </li>
        @endforeach
    </ul>
    @endif
</div>

@endsection
