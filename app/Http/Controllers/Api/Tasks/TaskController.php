<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Http\Collections\Tasks\TaskCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Tasks\Task;
use App\Repositories\Tasks\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = TaskRepository::index($request);

        return $this->collection(new TaskCollection($query));
    }

    public function store(TaskRequest $request)
    {
        $data = collect($request->validated());

        if ($companyId = auth()->user()->company_id) {
            $data->put('company_id', $companyId);
        }

        $task = Task::create($data->toArray());

        $this->saveThumbnail($task);
        $this->saveMedia($task);

        return $this->created(new TaskResource($task->fresh()));
    }

    public function show(Task $task)
    {
        $this->guardCompany($task->company_id);

        return $this->success(new TaskResource($task));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->guardCompany($task->company_id);

        $task->update($request->validated());
        $this->saveThumbnail($task);
        $this->saveMedia($task);

        $task->fresh();

        return $this->success(new TaskResource($task->fresh()));
    }

    protected function guardCompany($companyId)
    {
        $userCompanyId = auth()->user()->company_id;

        if ($userCompanyId && $companyId && $userCompanyId !== $companyId) {
            abort(404);
        }
    }
}
