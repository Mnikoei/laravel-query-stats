<?php

namespace Mnikoei\Qs\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Query extends Model
{
    use Sushi;

    public function getRows()
    {
        $path = storage_path('app/query-stats/qs.csv');

        if (! file_exists($path)) {
            file_put_contents($path, '');
        }

        $file = fopen(storage_path('app/query-stats/qs.csv'), 'r');

        $rows = [];
        while(! feof($file))
        {
            if ($row = fgetcsv($file)) {
                $rows[] = $this->normalizeRow($row);
            }
        }
        fclose($file);

        return $rows;
    }

    public function normalizeRow(array $row)
    {
        $row = $this->addKeys($row);
        $row['query'] = $this->removeSlashes($row['query']);
        $row['bindings'] = $this->removeSlashes($row['bindings']);

        return $row;
    }

    public function addKeys(array $row)
    {
        return array_combine([
            'query',
            'bindings',
            'time',
            'connection',
            'host',
            'database',
            'user',
            'executed_in',
            'queried_at'
        ], $row);
    }

    public function removeSlashes($str)
    {
        return stripslashes($str);
    }

    protected function sushiShouldCache()
    {
        return false;
    }
}