<?php

namespace Mnikoei\Pipeline;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    private $qc;

    public function register()
    {
        $this->qc = new QueryCollector();

        $this->app->singleton('qc', function (){
            return $this->qc;
        });
    }

    public function boot()
    {
        $this->qc->watchQueries();

        $this->app[Kernel::class]->pushMiddleware(QueryStore::class);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'qs');

        $this->registerRoutes();
    }

    public function registerRoutes()
    {
        $this->app['router']->get('query-stats', QsController::class)->name('_qs.show');

        $this->app['router']->get('qs/stats', function () {

            if (QueryStats::getAuth() === false) {
                return abort(403);
            }

            return QueryStats::getQueries();

        })->middleware('api');
    }
}