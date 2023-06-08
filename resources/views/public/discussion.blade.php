@php use Carbon\Carbon; @endphp
@php use TypiCMS\Modules\Forum\Helpers\ForumHelper; @endphp
@extends('core::public.master')
@section('mainContainerClass', 'main-forum')

@section('content')

    <div class="forum">

        <div class="forum-header">
            <div class="forum-header-container">
                <a class="forum-header-back-button" href="{{ route($lang.'::forum.home') }}">
                    <span class="visually-hidden">@lang('Back')</span>
                </a>
                <h1 class="forum-header-title">{{ $discussion->title }}</h1><span class="forum-header-details"> @lang('Posted in category')<a class="forum-header-category"
                                                                                                                                              href="{{ route($lang.'::forum.category.show', $discussion->category->slug) }}"
                                                                                                                                              style="background-color:{{ $discussion->category->color }}">{{ $discussion->category->name }}</a></span>
            </div>
        </div>

        @include('forum::public._alert')
        @include('forum::public._errors')

        <div class="forum-container">

            <ul class="forum-post-list">

                @foreach ($posts as $post)

                    <li class="forum-post" data-id="{{ $post->id }}">

                        <form class="forum-post-delete-alert" action="{{ route($lang.'::forum.posts.destroy', $post->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="delete">
                            <div class="forum-post-delete-alert-message">
                                <div class="forum-post-delete-alert-icon"></div>
                                @if ($loop->first)
                                    @lang('Are you sure you want to delete this <strong>discussion</strong>?')
                                @else
                                    @lang('Are you sure you want to delete this response?')
                                @endif
                            </div>
                            <button class="forum-post-delete-alert-cancel" type="button">@lang('No thanks')</button>
                            <button class="forum-post-delete-alert-confirm" type="submit">@lang('Yes delete it')</button>
                        </form>

                        <div class="forum-post-container">

                            <div class="forum-post-avatar" style="background-color:#{{ ForumHelper::stringToColorCode($post->user->first_name) }}">
                                {{ ucfirst(substr($post->user->first_name, 0, 1)) }}
                            </div>

                            <div class="forum-post-content">

                                <form class="forum-post-content-form" id="forum-post-form-edit-{{ $post->id }}" action="{{ route($lang.'::forum.posts.update', $post->id) }}" method="post">
                                    <input type="hidden" name="_method" value="patch">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <textarea class="forum-post-content-form-teaxtarea form-control" name="body" id="post-edit-{{ $post->id }}"></textarea>
                                    <div class="forum-post-content-form-actions forum-actions">
                                        <button class="forum-post-content-form-cancel" type="button" href="/" data-id="{{ $post->id }}">@lang('Cancel')</button>
                                        <button class="forum-post-content-form-submit" type="submit" data-id="{{ $post->id }}">
                                            <span class="forum-post-content-form-submit-icon"></span> @lang('Update response')
                                        </button>
                                    </div>
                                </form>

                                <div class="forum-post-content-wrapper">
                                    <div class="forum-post-content-info">
                                <span class="forum-post-content-info-container">
                                    <span class="forum-post-content-info-name">{{ $post->user->first_name.' '.$post->user->last_name }}</span>
                                    <span class="forum-post-content-info-date">{{ Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}</span>
                                </span>
                                        @if (!Auth::guest() && (Auth::user()->id == $post->user->id))
                                            <div class="forum-post-actions">
                                                <button class="forum-post-actions-button forum-post-actions-edit-button" type="button">
                                                    <span class="forum-post-actions-button-icon forum-post-actions-button-pencil"></span>
                                                    @lang('Edit')
                                                </button>
                                                <button class="forum-post-actions-button forum-post-actions-delete-button" type="button">
                                                    <span class="forum-post-actions-button-icon forum-post-actions-button-trash"></span>
                                                    @lang('Delete')
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="forum-post-content-text">{!! $post->body !!}</div>
                                    @if (is_array($post->files) && count($post->files) > 0)
                                        <div class="forum-post-content-files">
                                            <ul class="forum-post-content-files-list">
                                                @foreach ($post->files as $file)
                                                    <li class="forum-post-content-files-item">
                                                        <a class="forum-post-content-files-item-link" href="{{ route($lang.'::forum.file.download', ['file_path' => $file['path']]) }}">
                                                            <span class="forum-post-content-files-item-icon"></span>
                                                            <span class="forum-post-content-files-item-name">{{ $file['filename'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>

                    </li>

                @endforeach

            </ul>

            {{ $posts->links() }}

            @if (auth()->check())

                <form class="forum-new-post" id="new_response" action="{{ route($lang.'::forum.posts.store') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="forum_discussion_id" value="{{ $discussion->id }}">

                    <div class="forum-new-post-avatar" style="background-color:#{{ ForumHelper::stringToColorCode(Auth::user()->first_name) }}">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                    </div>

                    <div class="forum-new-post-editor" id="editor">
                        <div class="mb-3">
                            <label class="form-label" for="body">@lang('Type your response here…')</label>
                            <textarea class="form-control ckeditor-forum" id="body" name="body" placeholder="">{{ old('body') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="files">Documents</label>
                            <input class="form-control-file" type="file" name="files[]" id="files" multiple="multiple">
                        </div>
                        <div class="forum-discussion-actions forum-actions">
                            @if (config('typicms.modules.forum.email.enabled'))
                                <div class="forum-discussion-actions-notification forum-actions-notification">
                                    <div class="forum-actions-notification-loader spinner-border spinner-border-sm" id="forum-discussion-actions-notification-loader" role="status">
                                        <span class="visually-hidden">@lang('Loading…')</span>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email-notification"
                                               name="email-notification" @if (!Auth::guest() && $discussion->users->contains(Auth::user()->id))
                                            {{ 'checked' }}
                                            @endif>
                                        <label class="form-check-label" for="email-notification">@lang('Notify me when someone replies.')</label>
                                    </div>
                                </div>
                            @endif
                            <button class="btn btn-success" type="submit" id="submit_response"><i class="forum-new"></i> @lang('Submit response')</button>
                        </div>
                    </div>

                </form>

            @else

                <p class="forum-login-message">
                    <a href="{{ route($lang.'::login') }}">@lang('Please log in to post.')</a>
                </p>

            @endif

        </div>

    </div>

@stop

@push('js')
    <script src="{{ asset('components/ckeditor4/ckeditor.js') }}"></script>
    <script src="{{ asset('components/ckeditor4/config-forum.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailNotification = document.querySelector('#email-notification');
            const loader = document.querySelector('#forum-discussion-actions-notification-loader');

            emailNotification.addEventListener('change', () => {
                loader.classList.add('loading');
                const csrfToken = '{{ csrf_token() }}'; // Replace with actual CSRF token value
                const discussionId = '{{ $discussion->id }}'; // Replace with actual discussion ID value
                const url = '{{ route($lang.'::forum.discussion.email', $discussion->id) }}'; // Replace with actual URL

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': csrfToken
                    },
                    body: `_token=${encodeURIComponent(csrfToken)}`
                })
                    .then(() => {
                        loader.classList.remove('loading');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            const editButtons = document.querySelectorAll('.forum-post-actions-edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const parent = button.closest('li');
                    const id = parent.dataset.id;
                    const body = parent.querySelector('.forum-post-content-text');
                    parent.classList.add('editing');
                    document.querySelector(`#post-edit-${id}`).textContent = body.innerHTML;
                    CKEDITOR.replace(`post-edit-${id}`, editorConfig); // Replace with appropriate CKEditor configuration
                });
            });

            const deleteButtons = document.querySelectorAll('.forum-post-actions-delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const parent = button.closest('li');
                    parent.classList.add('deleting');
                    const id = parent.dataset.id;
                });
            });

            const cancelButtons = document.querySelectorAll('.forum-post-content-form-cancel');
            cancelButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const li = button.closest('li');
                    CKEDITOR.instances[`post-edit-${id}`].destroy();
                    li.classList.remove('editing');
                });
            });

            const deleteAlertCancelButtons = document.querySelectorAll('.forum-post-delete-alert-cancel');
            deleteAlertCancelButtons.forEach(button => {
                button.addEventListener('click', () => {
                    button.closest('li').classList.remove('deleting');
                });
            });
        });
    </script>

@endpush
