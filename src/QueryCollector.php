<?php


namespace Mnikoei\Pipeline;

class QueryCollector
{
    public $queries;

    public function watchQueries()
    {
        $db = app('db');

        $db->listen(
            function ($query, $bindings = null, $time = null, $connectionName = null) use ($db) {

                if ( $query instanceof \Illuminate\Database\Events\QueryExecuted ) {
                    $bindings = $query->bindings;
                    $time = $query->time;
                    $connection = $query->connection->getConfig();

                    $query = $query->sql;
                } else {
                    $connection = $db->connection($connectionName)->getConfig();
                }

                $this->queries[] = [
                    'query' => $query,
//                    'bindings' => $bindings,
                    'time' => $time,
                    'connection' => $connection['driver'],
                    'host' => $connection['host'],
                    'database' => $connection['database'],
                    'user' => $connection['username'],
                    'queried_at' => now()->toDateTimeString()
                ];
            }
        );
    }

    /**
     *
     */
    public function saveQueries()
    {
        $this->putCsv($this->stringify((array) $this->queries));
    }

    /**
     * @param array $rows
     * @return string
     */
    public function stringify(array $rows)
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
    public function putCsv(string $rows)
    {
        file_put_contents(storage_path('app/query-stats/qs.csv'), $rows, FILE_APPEND);
    }
}