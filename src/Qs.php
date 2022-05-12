<?php


namespace Mnikoei\Qs;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Qs
{
    private static $auth = true;

    public static $queries;

    public function watchQueries()
    {
        $db = app('db');

        $db->listen(
            function ($query, $bindings = null, $time = null, $connectionName = null) use ($db) {

                if ($query instanceof \Illuminate\Database\Events\QueryExecuted) {
                    $bindings = $query->bindings;
                    $time = $query->time;
                    $connection = $query->connection->getConfig();

                    $query = $query->sql;
                } else {
                    $connection = $db->connection($connectionName)->getConfig();
                }

                static::$queries[] = [
                    'query' => '"' . addslashes($query) . '"',
                    'bindings' => '"' . addslashes(implode(',', $bindings)) . '"',
                    'time' => $time,
                    'connection' => $connection['driver'] ?? 'unknown',
                    'host' => $connection['host'] ?? 'unknown',
                    'database' => $connection['database'] ?? 'unknown',
                    'user' => $connection['username'] ?? 'unknown',
                    'executed_in' => $this->findSourceLine(),
                    'queried_at' => now()->toDateTimeString()
                ];
            }
        );
    }

    public function findSourceLine()
    {
        try {
            $files = Arr::where($trace = debug_backtrace(), function ($item) use (&$x) {
                return Str::endsWith($item['file'] ?? '', 'Builder.php');
            });

            $file = $trace[$fileIndex = Arr::last(array_keys($files)) + 1];

            if (Str::endsWith($file['file'] ?? '', 'Facade.php')) {
                $file = $trace[$fileIndex + 1];
            }

            return $file['file'] . ':' . $file['line'];

        }catch (\Throwable $e) {
            return null;
        }
    }

    public static function auth(\Closure $callback)
    {
        static::$auth = (bool) $callback();
    }

    public static function getAuth()
    {
        return static::$auth;
    }

    /**
     *
     */
    public static function saveQueries()
    {
        static::putCsv(static::stringify((array) static::$queries));
    }

    /**
     * @param array $rows
     * @return string
     */
    public static function stringify(array $rows)
    {
        $rowsStr = '';
        foreach ($rows as $row) {
            $rowsStr .= implode(',', $row) . "\n";
        }

        return $rowsStr;
    }

    /**
     * @param string $rows
     */
    public static function putCsv(string $rows)
    {
        file_put_contents(storage_path('app/query-stats/qs.csv'), $rows, FILE_APPEND);
    }
}