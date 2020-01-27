<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Routing\Controller as Controller;
use TypiCMS\Modules\Forum\Models\Category;
use TypiCMS\Modules\Forum\Models\Discussion;

class PublicController extends Controller
{
    public function index()
    {
        $discussions = Discussion::with([
                'user',
                'post',
                'category',
            ])
            ->order()
            ->paginate(config('typicms.forum.per_page'));

        $categories = Category::order()->get();

        return view('forum::public.home', compact('discussions', 'categories'));
    }
}
