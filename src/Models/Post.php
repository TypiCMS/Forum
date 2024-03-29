<?php

namespace TypiCMS\Modules\Forum\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Core\Models\User;

class Post extends Base
{
    use SoftDeletes;

    protected $table = 'forum_posts';

    protected $fillable = [
        'forum_discussion_id',
        'user_id',
        'body',
        'files',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'files' => 'array',
    ];

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class, 'forum_discussion_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
