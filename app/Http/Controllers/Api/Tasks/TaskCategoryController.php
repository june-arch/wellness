<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Http\Collections\Tasks\TaskCategoryCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskCategoryRequest;
use App\Http\Resources\Tasks\TaskCategoryResource;
use App\Models\Tasks\TaskCategory;
use App\Repositories\Tasks\TaskCategoryRepository;
use Illuminate\Http\Request;

class TaskCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = TaskCategoryRepository::index($request);

        return $this->collection(new TaskCategoryCollection($query));
    }

    public function store(TaskCategoryRequest $request)
    {
        $data = collect($request->validated());

        if ($companyId = auth()->user()->company_id) {
            $data->put('company_id', $companyId);
        }

        $taskCategory = TaskCategory::create($data->toArray());
        return $this->created(new TaskCategoryResource($taskCategory));
    }

    public function show(TaskCategory $taskCategory)
    {
        $this->guardCompany($taskCategory->company_id);
        return $this->success(new TaskCategoryResource($taskCategory));
    }

    public function update(TaskCategoryRequest $request, TaskCategory $taskCategory)
    {
        $this->guardCompany($taskCategory->company_id);

        $taskCategory->update($request->validated());
        return $this->success(new TaskCategoryResource($taskCategory->fresh()));
    }

    protected function guardCompany($companyId)
    {
        $userCompanyId = auth()->user()->company_id;

        if ($userCompanyId && $companyId && $companyId !== $companyId) {
            abort(404);
        }
    }
}
