<?php

namespace TypiCMS\Modules\Forum\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Facades\TypiCMS;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Forum\Http\Controllers';

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
                $router->middleware('public')->group(function (Router $router) use ($page) {
                    $middleware = $page->private ? ['middleware' => 'auth'] : [];
                    $router->prefix($page->uri())->middleware($middleware)->group(function (Router $router) use ($page) {
                        $router->get('/', 'PublicController@index')->name('forum.home');
                        $router->get('category/{category}', 'PublicCategoryController@show')->name('forum.category.show');
                        $router->get('discussion/{category}/{discussion}', 'PublicDiscussionController@show')->name('forum.discussion.showInCategory');
                        $router->get('category/{category}/discussion/create', 'PublicDiscussionController@createInCategory')->name('forum.discussion.createInCategory');
                        $router->get('discussion/create', 'PublicDiscussionController@create')->name('forum.discussion.create');
                        $router->post('discussion', 'PublicDiscussionController@store')->name('forum.discussion.store');
                        $router->post('discussion/{discussion}/email', 'PublicDiscussionController@toggleEmailNotification')->name('forum.discussion.email');
                        $router->post('posts', 'PublicPostController@store')->name('forum.posts.store');
                        $router->get('download', 'PublicPostController@download')->name('forum.file.download');
                        $router->patch('posts/{post}', 'PublicPostController@update')->name('forum.posts.update');
                        $router->delete('posts/{post}', 'PublicPostController@destroy')->name('forum.posts.destroy');
                        $router->get('atom', 'PublicAtomController@index')->name('forum.atom');
                    });
                });
            }

            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('forum/categories', 'CategoriesAdminController@index')->name('admin::index-forum-categories')->middleware('can:read forum_categories');
                $router->get('forum/categories/create', 'CategoriesAdminController@create')->name('admin::create-forum-category')->middleware('can:create forum_categories');
                $router->get('forum/categories/{category}/edit', 'CategoriesAdminController@edit')->name('admin::edit-forum-category')->middleware('can:update forum_categories');
                $router->post('forum/categories', 'CategoriesAdminController@store')->name('admin::store-forum-category')->middleware('can:create forum_categories');
                $router->put('forum/categories/{category}', 'CategoriesAdminController@update')->name('admin::update-forum-category')->middleware('can:update forum_categories');

                $router->get('forum/discussions', 'DiscussionsAdminController@index')->name('admin::index-forum-discussions')->middleware('can:read forum_discussions');
                $router->get('forum/discussions/{discussion}', 'DiscussionsAdminController@show')->name('admin::show-forum-discussion')->middleware('can:read forum_discussions');
                $router->get('forum/discussions/{discussion}/edit', 'DiscussionsAdminController@edit')->name('admin::edit-forum-discussion')->middleware('can:edit forum_discussions');
                $router->post('forum/discussions', 'DiscussionsAdminController@store')->name('admin::store-forum-discussion')->middleware('can:create forum_discussions');
                $router->put('forum/discussions/{discussion}', 'DiscussionsAdminController@update')->name('admin::update-forum-discussion')->middleware('can:update forum_discussions');
            });

            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('forum/categories', 'CategoriesApiController@index')->middleware('can:read forum_categories');
                    $router->patch('forum/categories/{category}', 'CategoriesApiController@updatePartial')->middleware('can:update forum_categories');
                    $router->delete('forum/categories/{category}', 'CategoriesApiController@destroy')->middleware('can:delete forum_categories');

                    $router->get('forum/discussions', 'DiscussionsApiController@index')->middleware('can:read forum_discussions');
                    $router->delete('forum/discussions/{discussion}', 'DiscussionsApiController@destroy')->middleware('can:delete forum_discussions');
                });
            });
        });
    }
}
