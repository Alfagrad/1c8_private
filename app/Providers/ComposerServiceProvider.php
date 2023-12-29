<?php

namespace App\Providers;

use App\Http\ViewComposers\CartComposer;
use App\Http\ViewComposers\CatalogComposer;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\TopAlertComposer;
use App\Http\ViewComposers\TopAlertServiceComposer;
use App\Http\ViewComposers\CarrencyRateComposer;
use App\Http\ViewComposers\MiniCartComposer;
use App\Http\ViewComposers\InCartItemComposer;
use App\Http\ViewComposers\DebtCalculateComposer;
use App\Http\ViewComposers\ManagerDataComposer;
use App\Http\ViewComposers\IsServiceComposer;
use App\Http\ViewComposers\RepairsHeaderComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
//        view()->composer(['general.top_alert', 'general.top_alert_category'], TopAlertComposer::class);
//        view()->composer(['general.top_alert_service'], TopAlertServiceComposer::class);
        view()->composer(['*'], CarrencyRateComposer::class);
//        view()->composer(['general.nav-new'], MiniCartComposer::class);
////        view()->composer(['*'], InCartItemComposer::class);
//        view()->composer(['general.header-new'], DebtCalculateComposer::class);
//        view()->composer(['general.header-new'], ManagerDataComposer::class);
//        view()->composer(['*'], IsServiceComposer::class);
//        view()->composer(['general.header-new'], RepairsHeaderComposer::class);
        view()->composer(['includes.nav', 'item.index', 'item.line.dealer', 'item.line.service'], CartComposer::class);
//        view()->composer(['catalog.snippets.new_item_line', 'catalog.new_catalog'], CatalogComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CartComposer::class);
    }
}
