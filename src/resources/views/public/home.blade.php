@extends('core::public.master')
@section('mainContainerClass', 'main-forum')

@section('content')

<div class="forum">

    <div class="forum-hero">
        {!! $page->body !!}
    </div>

    @include('forum::public._alert')
    @include('forum::public._errors')

    <div class="forum-container">

        <div class="row">

            <div class="forum-sidebar col-md-3">
                <a class="forum-sidebar-create-button" href="{{ isset($currentCategory) ? route('forum.discussion.createInCategory', $currentCategory->slug) : route('forum.discussion.create') }}" class="btn btn-primary btn-block"><i class="forum-new"></i> @lang('New discussion')</a>
                @if ($categories->count() > 0)
                <ul class="forum-sidebar-category-list">
                    <li class="forum-sidebar-category-item forum-sidebar-category-item-all">
                        <a class="forum-sidebar-category-item-link {{ !isset($currentCategory) ? 'active' : '' }}" href="{{ route('forum.home') }}">
                            <span class="forum-sidebar-category-item-icon">
                                <span class="fa fa-comment-o fa-fw"></span>
                            </span>
                            <span class="forum-sidebar-category-item-name">@lang('All discussions')</span>
                        </a>
                    </li>
                    @foreach ($categories as $category)
                    <li class="forum-sidebar-category-item">
                        <a class="forum-sidebar-category-item-link {{ isset($currentCategory) && $category->slug === $currentCategory->slug ? 'active' : '' }}" href="{{ route('forum.category.show', $category->slug) }}">
                            <span class="forum-sidebar-category-item-icon">
                                <span class="forum-sidebar-category-item-box" style="background-color:{{ $category->color }}"></span>
                            </span>
                            <span class="forum-sidebar-category-item-name">{{ $category->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="col-md-9">
                @if ($discussions->count() === 0)
                <p class="text-muted">@lang('There are currently no discussions in this category.')</p>
                @else
                <ul class="forum-discussion-list">
                    @foreach($discussions as $discussion)
                    <li class="forum-discussion">
                        <a class="forum-discussion-link" href="{{ route('forum.discussion.showInCategory', [$discussion->category->slug, $discussion->slug]) }}">
                            <div class="forum-discussion-avatar" style="background-color:#<?= \TypiCMS\Modules\Forum\Helpers\ForumHelper::stringToColorCode($discussion->user->first_name); ?>">
                                {{ strtoupper(substr($discussion->user->first_name, 0, 1)) }}
                            </div>
                            <div class="forum-discussion-content">
                                <h2 class="forum-discussion-content-title">{{ $discussion->title }} <div class="forum-discussion-content-category" style="background-color:{{ $discussion->category->color }}">{{ $discussion->category->name }}</div></h2>
                                <div class="forum-discussion-content-info">@lang('Posted by') {{ $discussion->user->first_name.' '.$discussion->user->last_name }} {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}</div>
                                @if ($firstPost = $discussion->post->first() and $firstPost !== null)
                                <p class="forum-discussion-content-text">{{ substr(strip_tags($firstPost->body), 0, 200) }}@if(strlen(strip_tags($firstPost->body)) > 200){{ '…' }}@endif</p>
                                @endif
                            </div>
                            <div class="forum-discussion-count">
                                <span class="fa fa-comment-o fa-fw"></span> {{ $discussion->post->count()-1 }}
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
                {{ $discussions->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
