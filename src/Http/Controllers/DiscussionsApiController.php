<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Forum\Models\Discussion;

class DiscussionsApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Discussion::class)
            ->selectFields($request->input('fields.forum_discussions'))
            ->allowedSorts(['id', 'last_reply_at', 'title', 'views', 'forum_category_id'])
            ->allowedFilters([
                AllowedFilter::custom('title', new FilterOr()),
            ])
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function destroy(Discussion $discussion)
    {
        $discussion->delete();
    }
}
