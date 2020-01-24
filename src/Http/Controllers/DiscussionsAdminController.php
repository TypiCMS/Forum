<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Forum\Models\Discussion;

class DiscussionsAdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('forum::admin.index-discussions');
    }

    public function show(Discussion $discussion): View
    {
        return view('forum::admin.show-discussion')
            ->with(['model' => $discussion]);
    }

    public function edit(Discussion $discussion): RedirectResponse
    {
        return redirect()->route('admin::show-forum-discussion', $discussion);
    }
}
