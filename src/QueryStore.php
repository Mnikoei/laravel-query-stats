<?php


namespace Mnikoei\Qs;


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
        Qs::saveQueries();
    }
}