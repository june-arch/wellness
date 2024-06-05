<?php
namespace App\Repositories\Tasks;

use App\Models\Tasks\TaskTag;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class TaskTagRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = TaskTag::withCount('tasks')->with('company:id,name');

        if (!$request->filters) {
            $query->orderBy('name', 'desc');
        }

        $allowedData = [
            'filters'       => ['id', 'name'],
            'globalFilters' => ['id', 'name'],
            'orders'        => ['id', 'name'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}
