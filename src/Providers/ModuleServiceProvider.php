<?php

namespace TypiCMS\Modules\Forum\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Mews\Purifier\Facades\Purifier;
use Mews\Purifier\PurifierServiceProvider;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Forum\Composers\SidebarViewComposer;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms');
        $this->mergeConfigFrom(__DIR__.'/../config/permissions.php', 'typicms.permissions');

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['forum' => ['linkable_to_page']], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'forum');

        $this->publishes([
            __DIR__.'/../database/migrations/create_forum_tables.php.stub' => getMigrationFileName('create_forum_tables'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forum'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/scss' => resource_path('scss'),
        ], 'resources');

        $this->publishes([
            __DIR__.'/../../public' => public_path(),
        ], 'public');

        /*
         * Sidebar view composer
         */
        $this->app->view->composer('core::admin._sidebar', SidebarViewComposer::class);

        /*
         * Add the page in the view.
         */
        $this->app->view->composer('forum::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('forum');
        });
    }

    public function register()
    {
        $app = $this->app;

        $app->register(RouteServiceProvider::class);

        $app->register(PurifierServiceProvider::class);

        AliasLoader::getInstance()->alias('Purifier', Purifier::class);
    }
}
