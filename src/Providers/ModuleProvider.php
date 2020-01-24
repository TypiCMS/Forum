<?php

namespace TypiCMS\Modules\Forum\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Mews\Purifier\Facades\Purifier;
use Mews\Purifier\PurifierServiceProvider;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Forum\Composers\SidebarViewComposer;

class ModuleProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../config/permissions.php', 'typicms.permissions'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['forum' => ['linkable_to_page']], $modules));

        $this->loadTranslationsFrom(__DIR__.'/Lang', 'forum');
        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'forum');

        $this->publishes([
            __DIR__.'/../public/assets' => public_path('vendor/forum/assets'),
        ], 'forum_assets');

        $this->publishes([
            __DIR__.'/../config/forum.php' => config_path('forum.php'),
        ], 'forum_config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'forum_migrations');

        $this->publishes([
            __DIR__.'/../database/seeds/' => database_path('seeds'),
        ], 'forum_seeds');

        $this->publishes([
            __DIR__.'/Lang' => resource_path('lang/vendor/forum'),
        ], 'forum_lang');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forum'),
        ], 'views');

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

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        /*
         * Register the service provider for the dependency.
         */
        $app->register(PurifierServiceProvider::class);

        /*
         * Create aliases for the dependency.
         */
        AliasLoader::getInstance()->alias('Purifier', Purifier::class);
    }
}
