<?php

namespace TypiCMS\Modules\Forum\Presenters;

use TypiCMS\Modules\Core\Presenters\Presenter;

class CategoryPresenter extends Presenter
{
    public function title(): string
    {
        return $this->entity->name;
    }
}
