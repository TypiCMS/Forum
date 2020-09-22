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
            <a class="forum-header-back-button" href="{{ route($lang.'::forum.home') }}">
                <span class="sr-only">@lang('Back')</span>
            </a>
            <h1 class="forum-header-title">@lang('New discussion')</h1>
        </div>
    </div>

    @include('forum::public._alert')
    @include('forum::public._errors')

    <div class="forum-container">

        <form action="{{ route($lang.'::forum.discussion.store') }}" method="post" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="body">@lang('Type your discussion here…')</label>
                    <textarea id="body" class="form-control ckeditor-forum" name="body">{{ old('body') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="files">Documents</label>
                    <input class="form-control-file" type="file" name="files[]" id="files" multiple="multiple">
                </div>
            </div>
            <div class="forum-actions">
                <a class="btn btn-light mr-2" href="{{ route($lang.'::forum.home') }}" id="cancel_discussion">@lang('Cancel')</a>
                <button class="forum-actions-button-submit" type="submit" id="submit_discussion">
                    <span class="forum-actions-button-submit-icon"></span>
                    @lang('Create discussion')
                </button>
            </div>
        </form>

    </div>

</div>

@stop
