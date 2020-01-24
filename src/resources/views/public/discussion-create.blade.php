@extends('core::public.master')
@section('mainContainerClass', 'main-forum')

@push('js')
    <script src="{{ asset('components/ckeditor4/ckeditor.js') }}"></script>
    <script src="{{ asset('components/ckeditor4/config-forum.js') }}"></script>
@endpush

@section('content')

<div class="forum">

    <div class="forum-header">
        <div class="forum-header-container">
            <a class="forum-header-back-button" href="{{ route('forum.home') }}"><span class="fa fa-chevron-left fa-fw"></span></a>
            <h1 class="forum-header-title">@lang('New discussion')</h1>
        </div>
    </div>

    @include('forum::public._alert')
    @include('forum::public._errors')

    <div class="forum-container">

        <form action="{{ route('forum.discussion.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">@lang('Title')</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="forum_category_id">@lang('Category')</label>
                        <select id="forum_category_id" class="form-control custom-select" name="forum_category_id" required>
                            <option value=""></option>
                            @foreach($categories as $category)
                                @if(old('forum_category_id') == $category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                @elseif(!empty($currentCategory) && $currentCategory->id === $category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="body">@lang('Type your discussion here…')</label>
                <textarea id="body" class="form-control ckeditor-forum" name="body">{{ old('body') }}</textarea>
            </div>
            <div class="forum-actions">
                <a href="{{ route('forum.home') }}" class="btn btn-default" id="cancel_discussion">@lang('Cancel')</a>
                <button class="btn btn-success" type="submit" id="submit_discussion"><span class="fa fa-plus-circle fa-fw"></span> @lang('Create discussion')</button>
            </div>
        </form>

    </div>

</div>

@stop
