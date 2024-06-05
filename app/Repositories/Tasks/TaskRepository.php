<?php
namespace App\Repositories\Tasks;

use App\Models\Tasks\Task;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class TaskRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = Task::with('thumbnail', 'category:id,name', 'tags:id,name', 'createdBy:id,name', 'updatedBy:id,name');

        if (!$request->filters) {
            $query->orderBy('created_at', 'desc');
        }

        $allowedData = [
            'filters'       => ['id', 'name', 'type', 'is_active', 'category.name', 'createdBy.name', 'updatedBy.name'],
            'globalFilters' => ['name', 'type', 'is_active'],
            'orders'        => ['id', 'name', 'type', 'is_active'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}
