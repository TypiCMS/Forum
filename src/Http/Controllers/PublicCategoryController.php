<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Routing\Controller as Controller;
use TypiCMS\Modules\Forum\Models\Category;
use TypiCMS\Modules\Forum\Models\Discussion;

class PublicCategoryController extends Controller
{
    public function show(string $slug)
    {
        $discussionsQuery = Discussion::with([
                'user',
                'post',
                'category',
            ])
            ->order();

        $currentCategory = Category::where('slug', $slug)->firstOrFail();

        $discussionsQuery = $discussionsQuery->where('forum_category_id', $currentCategory->id);

        $discussions = $discussionsQuery->paginate(config('typicms.forum.per_page'));

        $categories = Category::order()->get();

        return view('forum::public.home', compact('discussions', 'categories', 'currentCategory'));
    }
}
