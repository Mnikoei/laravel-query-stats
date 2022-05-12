<?php


namespace Mnikoei\Qs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mnikoei\Qs\Models\Query;

class QsController extends Controller
{

    public function __construct()
    {
        if (! Qs::getAuth()) {
            return abort(403);
        }
    }

    public function __invoke()
    {
        return view('qs::query-stats', self::getQueries());
    }

    public static function getQueries()
    {
        return [
            'queries' => Query::paginate(),
            'pieChartData' => static::getPieChartData(Query::get()),
            'total' => Query::count(),
        ];
    }

    public static function getPieChartData($queries)
    {
        return collect($queries)->groupBy('query')->map(function ($query, $queryString) {
            return [
                'query' => $queryString,
                'count' => $query->count()
            ];
        })
            ->sortBy('count', SORT_REGULAR, true)
            ->take(20)
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