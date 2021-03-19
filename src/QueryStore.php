<?php


namespace Mnikoei\Pipeline;


class QueryStore
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        $this->saveQueries();

        return $response;
    }

    public function saveQueries()
    {
        app('qc')->saveQueries();
    }
}