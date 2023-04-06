<?php

namespace TypiCMS\Modules\Forum\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Core\Models\User;
use TypiCMS\Modules\Core\Traits\Historable;
use TypiCMS\Modules\Forum\Presenters\DiscussionPresenter;

class Discussion extends Base
{
    use SoftDeletes;
    use Historable;
    use PresentableTrait;

    protected $presenter = DiscussionPresenter::class;

    protected $table = 'forum_discussions';

    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'datetime',
        'last_reply_at' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'forum_category_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'forum_discussion_id');
    }

    public function postsWithTrashed(): HasMany
    {
        return $this->hasMany(Post::class, 'forum_discussion_id')->withTrashed()->orderBy('created_at');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'forum_notifications', 'discussion_id', 'user_id');
    }

    public function editUrl(): string
    {
        return route('admin::edit-forum-discussion', $this->id);
    }

    public function indexUrl(): string
    {
        return route('admin::index-forum-discussions');
    }
}
