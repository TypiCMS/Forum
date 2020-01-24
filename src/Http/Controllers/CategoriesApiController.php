<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Http\JsonResponse;
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

    protected function updatePartial(Category $category, Request $request): JsonResponse
    {
        $data = [];
        foreach ($request->all() as $column => $content) {
            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $data[$column.'->'.$key] = $value;
                }
            } else {
                $data[$column] = $content;
            }
        }

        foreach ($data as $key => $value) {
            $category->$key = $value;
        }
        $saved = $category->save();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $deleted = $category->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
