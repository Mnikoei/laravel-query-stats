<?php


namespace Mnikoei\Pipeline;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class QueryStats
{
    private static $auth = true;

    public static function auth(\Closure $callback)
    {
        static::$auth = (bool) $callback();
    }

    public static function getAuth()
    {
        return static::$auth;
    }

    public static function getQueries()
    {
        [$queries, $count] = self::getData();

        $queries = collect($queries)->sortBy('queried_at');

        return [
            'queries' => $queries,
            'pieChartData' => static::getPieChartData($queries),
            'total' => $count,
        ];
    }

    public static function getData()
    {
        $file = fopen(storage_path('app/query-stats/qs.csv'), 'r');

        $rows = [];
        while(! feof($file))
        {
            if ($row = fgetcsv($file)) {
                $rows[] = $row;
            }
        }

        fclose($file);

        $count = count($rows);

        $skip = ((request()->page ?: 1) - 1) * 100;
        $rows = array_slice($rows, $skip ?: 0, 100);

        return [self::mapKeys($rows), $count];
    }

    public static function getPieChartData(Collection $queries)
    {
        return $queries->groupBy('query')->map(function ($query, $queryString) {
            return [
                'query' => $queryString,
                'count' => $query->count()
            ];
        })
            ->sortBy('count', SORT_REGULAR, true)
            ->take(10)
            ->values();
    }

    public static function mapKeys(array $queriesData)
    {
        return array_map(function ($item) {
            return array_combine(
                ['query', 'time', 'connection', 'host', 'database', 'user', 'queried_at'], $item
            );
        }, $queriesData);
    }
}