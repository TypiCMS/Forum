<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use TypiCMS\Modules\Forum\Events\ForumAfterNewDiscussion;
use TypiCMS\Modules\Forum\Events\ForumBeforeNewDiscussion;
use TypiCMS\Modules\Forum\Models\Category;
use TypiCMS\Modules\Forum\Models\Discussion;
use TypiCMS\Modules\Forum\Models\Post;

class PublicDiscussionController extends Controller
{
    public function create()
    {
        $categories = Category::order()->all();

        return view('forum::public.discussion-create', compact('categories'));
    }

    public function createInCategory(string $category)
    {
        $categories = Category::order()->all();
        $currentCategory = Category::where('slug', $category)->first();

        return view('forum::public.discussion-create', compact('categories', 'currentCategory'));
    }

    public function store(Request $request)
    {
        $data = [
            'title' => strip_tags($request->title),
            'forum_category_id' => $request->forum_category_id,
            'body' => Purifier::clean($request->body),
        ];

        $validator = Validator::make($data, [
            'title' => 'required|min:5|max:255',
            'body' => 'required|min:10',
            'forum_category_id' => 'required',
        ], [
            'title.required' => trans('Please write a title.'),
            'title.min' => [
                'string' => trans('The title has to have at least :min characters.'),
            ],
            'title.max' => [
                'string' => trans('The title has to have no more than :max characters.'),
            ],
            'body.required' => trans('Please write some content.'),
            'body.min' => trans('The content has to have at least :min characters.'),
            'forum_category_id.required' => trans('Please choose a category.'),
        ]);

        event(new ForumBeforeNewDiscussion($request, $validator));
        if (function_exists('forum_before_new_discussion')) {
            forum_before_new_discussion($request, $validator);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userId = Auth::user()->id;

        if (config('typicms.forum.security.limit_time_between_posts')) {
            if ($this->notEnoughTimeBetweenDiscussion()) {
                $forum_alert = [
                    'forum_alert_type' => 'danger',
                    'forum_alert' => trans('In order to prevent spam, please allow at least :minutes minute(s) in between submitting content.', ['minutes' => config('typicms.forum.security.time_between_posts')]),
                ];

                return redirect()
                    ->route('forum.home')
                    ->with($forum_alert)
                    ->withInput();
            }
        }

        // Let’s guarantee that we always have a generic slug.
        $slug = Str::slug($data['title'], '-');

        $discussionExists = Discussion::where('slug', '=', $slug)->withTrashed()->first();
        $incrementer = 1;
        $newSlug = $slug;
        while (isset($discussionExists->id)) {
            $newSlug = $slug.'-'.$incrementer;
            $discussionExists = Discussion::where('slug', '=', $newSlug)->withTrashed()->first();
            ++$incrementer;
        }

        if ($slug !== $newSlug) {
            $slug = $newSlug;
        }

        $discussionData = [
            'title' => $data['title'],
            'forum_category_id' => $data['forum_category_id'],
            'user_id' => $userId,
            'slug' => $slug,
        ];

        $category = Category::find($data['forum_category_id']);
        if (!isset($category->slug)) {
            $category = Category::first();
        }

        $discussion = Discussion::create($discussionData);

        $postData = [
            'forum_discussion_id' => $discussion->id,
            'user_id' => $userId,
            'body' => $data['body'],
        ];

        // add the user to automatically be notified when new posts are submitted
        $discussion->users()->attach($userId);

        $post = Post::create($postData);

        if ($post->id) {
            event(new ForumAfterNewDiscussion($request, $discussion, $post));
            if (function_exists('forum_after_new_discussion')) {
                forum_after_new_discussion($request);
            }

            $forum_alert = [
                'forum_alert_type' => 'success',
                'forum_alert' => trans('Successfully created a new discussion.'),
            ];

            return redirect()
                ->route('forum.discussion.showInCategory', [$category->slug, $slug])
                ->with($forum_alert);
        } else {
            $forum_alert = [
                'forum_alert_type' => 'danger',
                'forum_alert' => trans('Whoops! There seems to be a problem creating your discussion.'),
            ];

            return redirect()
                ->route('forum.discussion.showInCategory', [$category->slug, $slug])
                ->with($forum_alert);
        }
    }

    private function notEnoughTimeBetweenDiscussion()
    {
        $user = Auth::user();

        $past = Carbon::now()->subMinutes(config('typicms.forum.security.time_between_posts'));

        $last_discussion = Discussion::where('user_id', '=', $user->id)->where('created_at', '>=', $past)->first();

        if (isset($last_discussion)) {
            return true;
        }

        return false;
    }

    public function show(string $category, string $slug)
    {
        $discussion = Discussion::where('slug', '=', $slug)->firstOrFail();
        $discussion_category = Category::find($discussion->forum_category_id);

        if ($category !== $discussion_category->slug) {
            return redirect()
                ->route('forum.discussion.showInCategory', [$discussion_category->slug, $discussion->slug]);
        }

        $posts = Post::with('user')
            ->where('forum_discussion_id', '=', $discussion->id)
            ->order()
            ->paginate(10);

        $discussion->increment('views');

        return view('forum::public.discussion', compact('discussion', 'posts'));
    }

    public function toggleEmailNotification(Discussion $discussion)
    {
        $userId = Auth::user()->id;

        // if it already exists, remove it
        if ($discussion->users->contains($userId)) {
            $discussion->users()->detach($userId);

            return response()->json(0);
        } else { // otherwise add it
            $discussion->users()->attach($userId);

            return response()->json(1);
        }
    }
}
