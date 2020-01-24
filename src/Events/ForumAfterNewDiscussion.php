<?php

namespace TypiCMS\Modules\Forum\Events;

use Illuminate\Http\Request;

class ForumAfterNewDiscussion
{
    public $request;

    public $discussion;

    public $post;

    public function __construct(Request $request, $discussion, $post)
    {
        $this->request = $request;
        $this->discussion = $discussion;
        $this->post = $post;
    }
}
