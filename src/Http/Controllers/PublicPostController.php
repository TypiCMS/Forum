<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use TypiCMS\Modules\Core\Services\FileUploader;
use TypiCMS\Modules\Forum\Events\ForumAfterNewResponse;
use TypiCMS\Modules\Forum\Events\ForumBeforeNewResponse;
use TypiCMS\Modules\Forum\Models\Category;
use TypiCMS\Modules\Forum\Models\Discussion;
use TypiCMS\Modules\Forum\Models\Post;
use TypiCMS\Modules\Forum\Notifications\ForumDiscussionUpdated;

class PublicPostController extends Controller
{
    public function store(Request $request, FileUploader $fileUploader)
    {
        $data = [
            'body' => Purifier::clean($request->body),
            'forum_discussion_id' => $request->forum_discussion_id,
            'user_id' => Auth::user()->id,
        ];

        $validator = Validator::make($data, [
            'body' => 'required|min:10',
        ], [
            'body.required' => trans('Please write some content.'),
            'body.min' => trans('The content has to have at least :min characters.'),
        ]);

        event(new ForumBeforeNewResponse($request, $validator));
        if (function_exists('forum_before_new_response')) {
            forum_before_new_response($request, $validator);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (config('typicms.modules.forum.security.limit_time_between_posts')) {
            if ($this->notEnoughTimeBetweenPosts()) {
                $forumAlert = [
                    'forum_alert_type' => 'danger',
                    'forum_alert' => trans('In order to prevent spam, please allow at least :minutes minute(s) in between submitting content.', ['minutes' => config('typicms.modules.forum.security.time_between_posts')]),
                ];

                return back()->with($forumAlert)->withInput();
            }
        }

        $data['files'] = [];
        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $data['files'][] = $fileUploader->handle($file, 'workspace', config('typicms.modules.forum.disk'));
            }
        }

        $newPost = Post::create($data);

        $discussion = Discussion::find($data['forum_discussion_id']);

        $category = Category::find($discussion->forum_category_id);
        if (!isset($category->slug)) {
            $category = Category::first();
        }

        if ($newPost->id) {
            $discussion->last_reply_at = $discussion->freshTimestamp();
            $discussion->save();

            event(new ForumAfterNewResponse($request, $newPost));
            if (function_exists('forum_after_new_response')) {
                forum_after_new_response($request);
            }

            // if email notifications are enabled
            if (config('typicms.modules.forum.email.enabled')) {
                // Send email notifications about new post
                $this->sendEmailNotifications($newPost->discussion);
            }

            $forumAlert = [
                'forum_alert_type' => 'success',
                'forum_alert' => trans('Response successfully submitted to discussion.'),
            ];

            return redirect()
                ->route(app()->getLocale().'::forum.discussion.showInCategory', [$category->slug, $discussion->slug])
                ->with($forumAlert);
        }
        $forumAlert = [
            'forum_alert_type' => 'danger',
            'forum_alert' => trans('Sorry, there seems to have been a problem submitting your response.'),
        ];

        return redirect()
            ->route(app()->getLocale().'::forum.discussion.showInCategory', [$category->slug, $discussion->slug])
            ->with($forumAlert);
    }

    public function download(Request $request)
    {
        $filePath = $request->input('file_path');

        if (!Storage::disk(config('typicms.modules.forum.disk'))->has($filePath)) {
            abort(404);
        }

        return Storage::disk(config('typicms.modules.forum.disk'))->download($filePath);
    }

    private function notEnoughTimeBetweenPosts()
    {
        $user = Auth::user();

        $past = Carbon::now()->subMinutes(config('typicms.modules.forum.security.time_between_posts'));

        $lastPost = Post::where('user_id', '=', $user->id)->where('created_at', '>=', $past)->first();

        if (isset($lastPost)) {
            return true;
        }

        return false;
    }

    private function sendEmailNotifications($discussion)
    {
        $users = $discussion->users->except(Auth::user()->id);
        foreach ($users as $user) {
            $user->notify(new ForumDiscussionUpdated($discussion));
        }
    }

    public function update(Post $post, Request $request)
    {
        $data = [
            'body' => Purifier::clean($request->body),
        ];
        $validator = Validator::make($data, [
            'body' => 'required|min:10',
        ], [
            'body.required' => trans('Please write some content.'),
            'body.min' => trans('The content has to have at least :min characters.'),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!Auth::guest() && Auth::user()->id == $post->user_id) {
            $post->body = $data['body'];
            $post->save();

            $discussion = Discussion::find($post->forum_discussion_id);

            $category = Category::find($discussion->forum_category_id);
            if (!isset($category->slug)) {
                $category = Category::first();
            }

            $forumAlert = [
                'forum_alert_type' => 'success',
                'forum_alert' => trans('Successfully updated the discussion.'),
            ];

            return redirect()
                ->route(app()->getLocale().'::forum.discussion.showInCategory', [$category->slug, $discussion->slug])
                ->with($forumAlert);
        }
        $forumAlert = [
            'forum_alert_type' => 'danger',
            'forum_alert' => trans('Could not update your response.'),
        ];

        return redirect()->route(app()->getLocale().'::forum.home')->with($forumAlert);
    }

    public function destroy(Post $post, Request $request)
    {
        if ($request->user()->id !== (int) $post->user_id) {
            return redirect()
                ->route(app()->getLocale().'::forum.home')
                ->with([
                    'forum_alert_type' => 'danger',
                    'forum_alert' => trans('Could not delete the response.'),
                ]);
        }

        if ($post->discussion->posts()->oldest()->first()->id === $post->id) {
            if (config('typicms.modules.forum.soft_deletes')) {
                $post->discussion->posts()->delete();
                $post->discussion()->delete();
            } else {
                $post->discussion->posts()->forceDelete();
                $post->discussion()->forceDelete();
            }

            return redirect()
                ->route(app()->getLocale().'::forum.home')
                ->with([
                    'forum_alert_type' => 'success',
                    'forum_alert' => trans('Successfully deleted the response and discussion.'),
                ]);
        }

        if (config('typicms.modules.forum.soft_deletes')) {
            $post->delete();
        } else {
            $post->forceDelete();
        }

        return redirect()
            ->route(app()->getLocale().'::forum.discussion.showInCategory', [$post->discussion->category->slug, $post->discussion->slug])
            ->with([
                'forum_alert_type' => 'success',
                'forum_alert' => trans('Successfully deleted the response from the discussion.'),
            ]);
    }
}
