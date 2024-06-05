<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Http\Collections\Tasks\TaskTagCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskTagRequest;
use App\Http\Resources\Tasks\TaskTagResource;
use App\Models\Tasks\TaskTag;
use App\Repositories\Tasks\TaskTagRepository;
use Illuminate\Http\Request;

class TaskTagController extends Controller
{
    public function index(Request $request)
    {
        $query = TaskTagRepository::index($request);

        return $this->collection(new TaskTagCollection($query));
    }

    public function store(TaskTagRequest $request)
    {
        $data = collect($request->validated());

        if ($companyId = auth()->user()->company_id) {
            $data->put('company_id', $companyId);
        }

        $taskTag = TaskTag::create($data->toArray());
        return $this->created(new TaskTagResource($taskTag));
    }

    public function show(TaskTag $taskTag)
    {
        $this->guardCompany($taskTag->company_id);
        return $this->success(new TaskTagResource($taskTag));
    }

    public function update(TaskTagRequest $request, TaskTag $taskTag)
    {
        $this->guardCompany($taskTag->company_id);

        $taskTag->update($request->validated());
        return $this->success(new TaskTagResource($taskTag->fresh()));
    }

    protected function guardCompany($companyId)
    {
        $userCompanyId = auth()->user()->company_id;

        if ($userCompanyId && $companyId && $companyId !== $companyId) {
            abort(404);
        }
    }
}
