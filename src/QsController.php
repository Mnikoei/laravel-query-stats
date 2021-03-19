<?php


namespace Mnikoei\Pipeline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QsController extends Controller
{
    public function __construct()
    {
        if (QueryStats::getAuth() === false) {
            return abort(403);
        }
    }

    public function __invoke()
    {
        return view('qs::query-stats', QueryStats::getQueries());
    }
}