<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Forum\Models\Category;

class CategoriesApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Category::class)
            ->selectFields($request->input('fields.forum_categories'))
            ->allowedSorts(['id', 'position', 'name_translated', 'color'])
            ->allowedFilters([
                AllowedFilter::custom('name,color', new FilterOr()),
            ])
            ->paginate($request->input('per_page'));

        return $data;
    }

    protected function updatePartial(Category $category, Request $request)
    {
        foreach ($request->only('status', 'position') as $key => $content) {
            if ($category->isTranslatableAttribute($key)) {
                foreach ($content as $lang => $value) {
                    $category->setTranslation($key, $lang, $value);
                }
            } else {
                $category->{$key} = $content;
            }
        }

        $category->save();
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }
}
