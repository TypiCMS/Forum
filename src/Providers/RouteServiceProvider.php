<?php

namespace TypiCMS\Modules\Forum\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Forum\Http\Controllers\CategoriesAdminController;
use TypiCMS\Modules\Forum\Http\Controllers\CategoriesApiController;
use TypiCMS\Modules\Forum\Http\Controllers\DiscussionsAdminController;
use TypiCMS\Modules\Forum\Http\Controllers\DiscussionsApiController;
use TypiCMS\Modules\Forum\Http\Controllers\PublicAtomController;
use TypiCMS\Modules\Forum\Http\Controllers\PublicCategoryController;
use TypiCMS\Modules\Forum\Http\Controllers\PublicController;
use TypiCMS\Modules\Forum\Http\Controllers\PublicDiscussionController;
use TypiCMS\Modules\Forum\Http\Controllers\PublicPostController;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        /*
         * Front office routes
         */
        if ($page = TypiCMS::getPageLinkedToModule('forum')) {
            $middleware = $page->private ? ['public', 'auth'] : ['public'];
            foreach (locales() as $lang) {
                if ($page->isPublished($lang) && $uri = $page->uri($lang)) {
                    Route::middleware($middleware)->prefix($uri)->name($lang.'::')->group(function (Router $router) {
                        $router->get('/', [PublicController::class, 'index'])->name('forum.home');
                        $router->get('category/{category}', [PublicCategoryController::class, 'show'])->name('forum.category.show');
                        $router->get('discussion/{category}/{discussion}', [PublicDiscussionController::class, 'show'])->name('forum.discussion.showInCategory');
                        $router->get('category/{category}/discussion/create', [PublicDiscussionController::class, 'createInCategory'])->name('forum.discussion.createInCategory');
                        $router->get('discussion/create', [PublicDiscussionController::class, 'create'])->name('forum.discussion.create');
                        $router->post('discussion', [PublicDiscussionController::class, 'store'])->name('forum.discussion.store');
                        $router->post('discussion/{discussion}/email', [PublicDiscussionController::class, 'toggleEmailNotification'])->name('forum.discussion.email');
                        $router->post('posts', [PublicPostController::class, 'store'])->name('forum.posts.store');
                        $router->get('download', [PublicPostController::class, 'download'])->name('forum.file.download');
                        $router->patch('posts/{post}', [PublicPostController::class, 'update'])->name('forum.posts.update');
                        $router->delete('posts/{post}', [PublicPostController::class, 'destroy'])->name('forum.posts.destroy');
                        $router->get('atom', [PublicAtomController::class, 'index'])->name('forum.atom');
                    });
                }
            }
        }

        /*
         * Admin routes
         */
        Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
            $router->get('forum/categories', [CategoriesAdminController::class, 'index'])->name('index-forum-categories')->middleware('can:read forum_categories');
            $router->get('forum/categories/create', [CategoriesAdminController::class, 'create'])->name('create-forum-category')->middleware('can:create forum_categories');
            $router->get('forum/categories/{category}/edit', [CategoriesAdminController::class, 'edit'])->name('edit-forum-category')->middleware('can:update forum_categories');
            $router->post('forum/categories', [CategoriesAdminController::class, 'store'])->name('store-forum-category')->middleware('can:create forum_categories');
            $router->put('forum/categories/{category}', [CategoriesAdminController::class, 'update'])->name('update-forum-category')->middleware('can:update forum_categories');

            $router->get('forum/discussions', [DiscussionsAdminController::class, 'index'])->name('index-forum-discussions')->middleware('can:read forum_discussions');
            $router->get('forum/discussions/{discussion}', [DiscussionsAdminController::class, 'show'])->name('show-forum-discussion')->middleware('can:read forum_discussions');
            $router->get('forum/discussions/{discussion}/edit', [DiscussionsAdminController::class, 'edit'])->name('edit-forum-discussion')->middleware('can:edit forum_discussions');
            $router->post('forum/discussions', [DiscussionsAdminController::class, 'store'])->name('store-forum-discussion')->middleware('can:create forum_discussions');
            $router->put('forum/discussions/{discussion}', [DiscussionsAdminController::class, 'update'])->name('update-forum-discussion')->middleware('can:update forum_discussions');
        });

        /*
         * API routes
         */
        Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router) {
            $router->get('forum/categories', [CategoriesApiController::class, 'index'])->middleware('can:read forum_categories');
            $router->patch('forum/categories/{category}', [CategoriesApiController::class, 'updatePartial'])->middleware('can:update forum_categories');
            $router->delete('forum/categories/{category}', [CategoriesApiController::class, 'destroy'])->middleware('can:delete forum_categories');

            $router->get('forum/discussions', [DiscussionsApiController::class, 'index'])->middleware('can:read forum_discussions');
            $router->delete('forum/discussions/{discussion}', [DiscussionsApiController::class, 'destroy'])->middleware('can:delete forum_discussions');
        });
    }
}
