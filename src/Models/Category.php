<?php

namespace TypiCMS\Modules\Forum\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Forum\Presenters\CategoryPresenter;
use TypiCMS\Modules\History\Traits\Historable;

class Category extends Base implements Sortable
{
    use SoftDeletes;
    use HasTranslations;
    use Historable;
    use PresentableTrait;
    use SortableTrait;

    protected $presenter = CategoryPresenter::class;

    protected $table = 'forum_categories';

    protected $guarded = ['id', 'exit'];

    public $translatable = [
        'name',
        'slug',
    ];

    public $sortable = [
        'order_column_name' => 'position',
    ];

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class, 'forum_category_id');
    }

    public function editUrl(): string
    {
        return route('admin::edit-forum-category', $this->id);
    }

    public function indexUrl(): string
    {
        return route('admin::index-forum-categories');
    }
}
