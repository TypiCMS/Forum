<?php

namespace TypiCMS\Modules\Forum\Events;

use Illuminate\Http\Request;

class ForumAfterNewResponse
{
    public $request;

    public $post;

    public function __construct(Request $request, $post)
    {
        $this->request = $request;
        $this->post = $post;
    }
}
