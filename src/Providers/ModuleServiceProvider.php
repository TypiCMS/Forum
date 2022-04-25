<?php

namespace TypiCMS\Modules\Forum\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\View;
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
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.modules');

        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'forum');

        $this->publishes([__DIR__.'/../../database/migrations/create_forum_tables.php.stub' => getMigrationFileName('create_forum_tables')], 'typicms-migrations');
        $this->publishes([__DIR__.'/../../resources/views' => resource_path('views/forum')], 'typicms-views');

        $this->publishes([__DIR__.'/../../resources/scss' => resource_path('scss')], 'typicms-resources');
        $this->publishes([__DIR__.'/../../public' => public_path()], 'typicms-public');

        View::composer('core::admin._sidebar', SidebarViewComposer::class);

        /*
         * Add the page in the view.
         */
        View::composer('forum::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('forum');
        });
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(PurifierServiceProvider::class);

        AliasLoader::getInstance()->alias('Purifier', Purifier::class);
    }
}
