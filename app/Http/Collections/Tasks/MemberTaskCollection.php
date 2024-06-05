<?php

namespace App\Http\Collections\Tasks;

use App\Http\Collections\Collection;

class MemberTaskCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->merge(collect($item->task)->except('id'))
                ->except('task', 'user_id', 'task_id', 'category_id', 'time')
                ->put('description', trim(substr(explode('\n\n', $item->task->description)[0], 0, 100)))
                ->put('category', $item->task->category->name)
                ->put('thumbnail', $item->task->thumbnailUrl);
        });

        return parent::toArray($request);
    }
}
