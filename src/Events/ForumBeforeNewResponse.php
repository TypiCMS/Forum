<?php

namespace TypiCMS\Modules\Forum\Events;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class ForumBeforeNewResponse
{
    public $request;

    public $validator;

    public function __construct(Request $request, Validator $validator)
    {
        $this->request = $request;
        $this->validator = $validator;
    }
}
