<?php

namespace App\Repositories\Tasks;

use App\Models\Companies\Company;
use App\Models\Tasks\Task;
use App\Models\Tasks\UserTask;
use App\Models\Users\Member;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class UserTaskRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = UserTask::where('user_id', $request->user()->id)
            ->with('task:id,name,description,category_id', 'task.thumbnail', 'task.category:id,name');

        if (!$request->filters) {
            $query->orderBy('date', 'desc');
        }

        $allowedData = [
            'filters'       => ['id', 'is_compelete', 'date', 'task.name', 'task.category.name'],
            'globalFilters' => ['is_complete', 'date'],
            'orders'        => ['is_compelete', 'date'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }

    public static function generateWeeklyTasks()
    {
        $tasks   = Task::where('is_active', true)->take(21)->inRandomOrder()->get();
        $members = Member::where('is_active', true)->whereHas('company', function ($q) {
            $q->where('is_active', true)->where('expires_at', '>=', now());
        })->get();

        self::generateWeeklyData($members, $tasks);
    }

    public static function generateDailyTasks()
    {
        $tasks   = Task::where('is_active', true)->take(9)->inRandomOrder()->get();
        $members = Member::where('is_active', true)->whereHas('company', function ($q) {
            $q->where('is_active', true)->where('expires_at', '>=', now());
        })->get();

        self::generateWeeklyData($members, $tasks);
    }

    public static function generateMemberOfCompanyTasks(Company $company)
    {
        $tasks   = Task::where('is_active', true)->take(21)->inRandomOrder()->get();
        $members = Member::where('is_active', true)->where('company_id', $company->id)->whereHas('company', function ($q) {
            $q->where('is_active', true)->where('expires_at', '>=', now());
        })->get();

        self::generateWeeklyData($members, $tasks);
    }

    protected static function generateWeeklyData($members, $tasks)
    {
        $chunkedTasks = collect($tasks)->chunk(3);

        $day       = now();
        $totalDays = now()->dayOfWeek;

        for ($i = 0; $i <= 7 - $totalDays; $i++) {
            foreach ($members as $member) {

                foreach ($chunkedTasks as $tasks) {
                    $time = 0;

                    foreach ($tasks as $task) {
                        $time++;

                        UserTask::insert([
                            'user_id' => $member->id,
                            'task_id' => $task->id,
                            'date'    => $day->format('Y-m-d'),
                            'time'    => $time,
                        ]);
                    }
                }
            }

            $day = $day->add('day', 1);
        }
    }

    protected static function generateDailyData($members, $tasks)
    {
        $chunkedTasks = collect($tasks)->chunk(3);

        $day = now();

        foreach ($members as $member) {
            foreach ($chunkedTasks as $tasks) {
                $time = 0;

                foreach ($tasks as $task) {
                    $time++;

                    UserTask::insert([
                        'user_id' => $member->id,
                        'task_id' => $task->id,
                        'date'    => $day->format('Y-m-d'),
                        'time'    => $time,
                    ]);
                }
            }
        }
    }

    public static function getDailyMemberTask(Member $member)
    {
        $tasks = UserTask::where('user_id', $member->id)
            ->with('task:id,name,description,category_id', 'task.thumbnail', 'task.category:id,name')
            ->where('date', now()->format('Y-m-d'))
            ->get()->map(function ($item) {

            return collect($item)
                ->merge(collect($item->task)->except('id'))
                ->except('task', 'user_id', 'task_id', 'category_id')
                ->put('description', trim(substr(explode('\n\n', $item->task->description)[0], 0, 100)))
                ->put('category', $item->task->category->name)
                ->put('thumbnail', $item->task->thumbnailUrl);
        });

        return $tasks;
    }
}
