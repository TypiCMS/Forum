<?php

namespace TypiCMS\Modules\Forum\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class CategoryFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'color' => 'required|max:7',
            'slug' => 'required|max:255',
        ];
    }
}
