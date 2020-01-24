<?php

namespace TypiCMS\Modules\Forum\Events;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class ForumBeforeNewDiscussion
{
    public $request;

    public $validator;

    public function __construct(Request $request, Validator $validator)
    {
        $this->request = $request;
        $this->validator = $validator;
    }
}
