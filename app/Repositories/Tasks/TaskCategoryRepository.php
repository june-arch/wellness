<?php
namespace App\Repositories\Tasks;

use App\Models\Tasks\TaskCategory;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class TaskCategoryRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = TaskCategory::withCount('tasks')->with('company:id,name');

        if (!$request->filters) {
            $query->orderBy('name', 'desc');
        }

        $allowedData = [
            'filters'       => ['id', 'name', 'description'],
            'globalFilters' => ['id', 'name', 'description'],
            'orders'        => ['id', 'name', 'description'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}
