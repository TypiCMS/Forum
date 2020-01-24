<?php

namespace TypiCMS\Modules\Forum\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class DiscussionFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'forum_category_id' => 'integer',
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:255|required_with:title',
            'user_id' => 'required|integer',
            'sticky' => 'boolean',
            'views' => 'integer',
            'answered' => 'boolean',
        ];
    }
}
