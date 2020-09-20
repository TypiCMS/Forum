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
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('forum')) {
                $middleware = $page->private ? ['public', 'auth'] : ['public'];
                foreach (locales() as $lang) {
                    if ($page->translate('status', $lang) && $uri = $page->uri($lang)) {
                        $router->prefix($uri)->middleware($middleware)->group(function (Router $router) use ($page, $lang) {
                            $router->get('/', [PublicController::class, 'index'])->name($lang.'::forum.home');
                            $router->get('category/{category}', [PublicCategoryController::class, 'show'])->name($lang.'::forum.category.show');
                            $router->get('discussion/{category}/{discussion}', [PublicDiscussionController::class, 'show'])->name($lang.'::forum.discussion.showInCategory');
                            $router->get('category/{category}/discussion/create', [PublicDiscussionController::class, 'createInCategory'])->name($lang.'::forum.discussion.createInCategory');
                            $router->get('discussion/create', [PublicDiscussionController::class, 'create'])->name($lang.'::forum.discussion.create');
                            $router->post('discussion', [PublicDiscussionController::class, 'store'])->name($lang.'::forum.discussion.store');
                            $router->post('discussion/{discussion}/email', [PublicDiscussionController::class, 'toggleEmailNotification'])->name($lang.'::forum.discussion.email');
                            $router->post('posts', [PublicPostController::class, 'store'])->name($lang.'::forum.posts.store');
                            $router->get('download', [PublicPostController::class, 'download'])->name($lang.'::forum.file.download');
                            $router->patch('posts/{post}', [PublicPostController::class, 'update'])->name($lang.'::forum.posts.update');
                            $router->delete('posts/{post}', [PublicPostController::class, 'destroy'])->name($lang.'::forum.posts.destroy');
                            $router->get('atom', [PublicAtomController::class, 'index'])->name($lang.'::forum.atom');
                        });
                    }
                }
            }

            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('forum/categories', [CategoriesAdminController::class, 'index'])->name('admin::index-forum-categories')->middleware('can:read forum_categories');
                $router->get('forum/categories/create', [CategoriesAdminController::class, 'create'])->name('admin::create-forum-category')->middleware('can:create forum_categories');
                $router->get('forum/categories/{category}/edit', [CategoriesAdminController::class, 'edit'])->name('admin::edit-forum-category')->middleware('can:update forum_categories');
                $router->post('forum/categories', [CategoriesAdminController::class, 'store'])->name('admin::store-forum-category')->middleware('can:create forum_categories');
                $router->put('forum/categories/{category}', [CategoriesAdminController::class, 'update'])->name('admin::update-forum-category')->middleware('can:update forum_categories');

                $router->get('forum/discussions', [DiscussionsAdminController::class, 'index'])->name('admin::index-forum-discussions')->middleware('can:read forum_discussions');
                $router->get('forum/discussions/{discussion}', [DiscussionsAdminController::class, 'show'])->name('admin::show-forum-discussion')->middleware('can:read forum_discussions');
                $router->get('forum/discussions/{discussion}/edit', [DiscussionsAdminController::class, 'edit'])->name('admin::edit-forum-discussion')->middleware('can:edit forum_discussions');
                $router->post('forum/discussions', [DiscussionsAdminController::class, 'store'])->name('admin::store-forum-discussion')->middleware('can:create forum_discussions');
                $router->put('forum/discussions/{discussion}', [DiscussionsAdminController::class, 'update'])->name('admin::update-forum-discussion')->middleware('can:update forum_discussions');
            });

            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('forum/categories', [CategoriesApiController::class, 'index'])->middleware('can:read forum_categories');
                    $router->patch('forum/categories/{category}', [CategoriesApiController::class, 'updatePartial'])->middleware('can:update forum_categories');
                    $router->delete('forum/categories/{category}', [CategoriesApiController::class, 'destroy'])->middleware('can:delete forum_categories');

                    $router->get('forum/discussions', [DiscussionsApiController::class, 'index'])->middleware('can:read forum_discussions');
                    $router->delete('forum/discussions/{discussion}', [DiscussionsApiController::class, 'destroy'])->middleware('can:delete forum_discussions');
                });
            });
        });
    }
}
