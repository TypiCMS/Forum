<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Forum\Http\Requests\CategoryFormRequest;
use TypiCMS\Modules\Forum\Models\Category;

class CategoriesAdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('forum::admin.index-categories');
    }

    public function create(): View
    {
        $model = new Category();

        return view('forum::admin.create-category')
            ->with(compact('model'));
    }

    public function edit(Category $category): View
    {
        return view('forum::admin.edit-category')
            ->with(['model' => $category]);
    }

    public function store(CategoryFormRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        return $this->redirect($request, $category);
    }

    public function update(Category $category, CategoryFormRequest $request): RedirectResponse
    {
        $category->update($request->validated());

        return $this->redirect($request, $category);
    }
}
