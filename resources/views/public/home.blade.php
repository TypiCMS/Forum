@php
    use TypiCMS\Modules\Forum\Helpers\ForumHelper;
@endphp

@php
    use Carbon\Carbon;
@endphp

@extends('core::public.master')
@section('mainContainerClass', 'main-forum')

@section("content")
    <div class="forum">
        <div class="forum-hero">
            {!! $page->body !!}
        </div>

        @include('forum::public._alert')
        @include('forum::public._errors')

        <div class="forum-container">
            <div class="row">
                <div class="forum-sidebar col-md-3">
                    <a
                        class="forum-sidebar-create-button"
                        href="{{ isset($currentCategory) ? route($lang . '::forum.discussion.createInCategory', $currentCategory->slug) : route($lang . '::forum.discussion.create') }}"
                        class="btn btn-primary btn-block"
                    >
                        <i class="forum-new"></i>
                        @lang('New discussion')
                    </a>
                    @if ($categories->count() > 0)
                        <ul class="forum-sidebar-category-list">
                            <li class="forum-sidebar-category-item forum-sidebar-category-item-all">
                                <a class="forum-sidebar-category-item-link {{ ! isset($currentCategory) ? 'active' : '' }}" href="{{ route($lang . '::forum.home') }}">
                                    <svg class="forum-sidebar-category-item-icon" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M896 384q-204 0-381.5 69.5t-282 187.5-104.5 255q0 112 71.5 213.5t201.5 175.5l87 50-27 96q-24 91-70 172 152-63 275-171l43-38 57 6q69 8 130 8 204 0 381.5-69.5t282-187.5 104.5-255-104.5-255-282-187.5-381.5-69.5zm896 512q0 174-120 321.5t-326 233-450 85.5q-70 0-145-8-198 175-460 242-49 14-114 22h-5q-15 0-27-10.5t-16-27.5v-1q-3-4-.5-12t2-10 4.5-9.5l6-9 7-8.5 8-9q7-8 31-34.5t34.5-38 31-39.5 32.5-51 27-59 26-76q-157-89-247.5-220t-90.5-281q0-174 120-321.5t326-233 450-85.5 450 85.5 326 233 120 321.5z"
                                        />
                                    </svg>
                                    <span class="forum-sidebar-category-item-name">@lang('All discussions')</span>
                                </a>
                            </li>
                            @foreach ($categories as $category)
                                <li class="forum-sidebar-category-item">
                                    <a
                                        class="forum-sidebar-category-item-link {{ isset($currentCategory) && $category->slug === $currentCategory->slug ? 'active' : '' }}"
                                        href="{{ route($lang . '::forum.category.show', $category->slug) }}"
                                    >
                                        <span class="forum-sidebar-category-item-icon">
                                            <span class="forum-sidebar-category-item-box" style="background-color: {{ $category->color }}"></span>
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
                            @foreach ($discussions as $discussion)
                                <li class="forum-discussion">
                                    <a class="forum-discussion-link" href="{{ route($lang . '::forum.discussion.showInCategory', [$discussion->category->slug, $discussion->slug]) }}">
                                        <div class="forum-discussion-avatar" style="background-color: #{{ ForumHelper::stringToColorCode($discussion->user->first_name) }}">
                                            {{ strtoupper(substr($discussion->user->first_name, 0, 1)) }}
                                        </div>
                                        <div class="forum-discussion-content">
                                            <h2 class="forum-discussion-content-title">
                                                {{ $discussion->title }}
                                                <div class="forum-discussion-content-category" style="background-color: {{ $discussion->category->color }}">
                                                    {{ $discussion->category->name }}
                                                </div>
                                            </h2>
                                            <div class="forum-discussion-content-info">
                                                @lang('Posted by')
                                                {{ $discussion->user->first_name . " " . $discussion->user->last_name }}
                                                {{ Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}
                                            </div>
                                            @if (($firstPost = $discussion->posts->first()) and $firstPost !== null)
                                                <p class="forum-discussion-content-text">
                                                    {{ substr(strip_tags($firstPost->body), 0, 200) }}@if (strlen(strip_tags($firstPost->body)) > 200){{ "…" }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                        <div class="forum-discussion-count">
                                            <svg class="forum-discussion-count-icon" fill="currentColor" width="16" height="16" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M896 384q-204 0-381.5 69.5t-282 187.5-104.5 255q0 112 71.5 213.5t201.5 175.5l87 50-27 96q-24 91-70 172 152-63 275-171l43-38 57 6q69 8 130 8 204 0 381.5-69.5t282-187.5 104.5-255-104.5-255-282-187.5-381.5-69.5zm896 512q0 174-120 321.5t-326 233-450 85.5q-70 0-145-8-198 175-460 242-49 14-114 22h-5q-15 0-27-10.5t-16-27.5v-1q-3-4-.5-12t2-10 4.5-9.5l6-9 7-8.5 8-9q7-8 31-34.5t34.5-38 31-39.5 32.5-51 27-59 26-76q-157-89-247.5-220t-90.5-281q0-174 120-321.5t326-233 450-85.5 450 85.5 326 233 120 321.5z"
                                                />
                                            </svg>
                                            {{ $discussion->posts->count() - 1 }}
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
