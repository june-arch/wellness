<?php

namespace App\Http\Controllers\MemberApi\Tasks;

use App\Http\Collections\Tasks\MemberTaskCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tasks\MemberTaskResource;
use App\Models\Tasks\UserTask;
use App\Repositories\Tasks\UserTaskRepository;
use Illuminate\Http\Request;

class MemberTaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = UserTaskRepository::getDailyMemberTask($user);
        $morning = [];
        $afternoon = [];
        $night = [];
        foreach ($tasks as $task) {
            if ($task['time'] == '1') {
                $morning[] = $task;
            }
            if ($task['time'] == '2') {
                $afternoon[] = $task;
            }
            if ($task['time'] == '3') {
                $night[] = $task;
            }
        }
        $data = [
            'pagi' => $morning,
            'siang' => $afternoon,
            'malam' => $night,
        ];
        return $this->success($data);
    }

    public function history(Request $request)
    {
        $query = UserTaskRepository::index($request);

        return $this->collection(new MemberTaskCollection($query));
    }

    public function show(UserTask $userTask)
    {
        $this->guardUser($userTask);
        return $this->success(new MemberTaskResource($userTask));
    }

    public function complete(UserTask $userTask)
    {
        $this->guardUser($userTask);
        $userTask->update(['is_complete' => true]);
        return $this->success(new MemberTaskResource($userTask->fresh()));
    }

    public function uncomplete(UserTask $userTask)
    {
        $this->guardUser($userTask);
        $userTask->update(['is_complete' => false]);
        return $this->success(new MemberTaskResource($userTask->fresh()));
    }

    protected function guardUser(UserTask $task)
    {
        $userId = auth()->user()->id;

        if ($userId && $task->user_id && $userId !== $task->user_id) {
            abort(404);
        }
    }
}
