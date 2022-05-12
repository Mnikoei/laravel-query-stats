<?php

namespace Mnikoei\Qs;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    private $qs;

    public function register()
    {
        $this->qs = new Qs();

        $this->app->singleton('qc', function (){
            return $this->qs;
        });
    }

    public function boot()
    {
        $this->qs->watchQueries();

        $this->app[Kernel::class]->pushMiddleware(QueryStore::class);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'qs');

        $this->registerRoutes();
    }

    public function registerRoutes()
    {
        $this->app['router']->get('query-stats', QsController::class)->name('_qs.show');
    }
}