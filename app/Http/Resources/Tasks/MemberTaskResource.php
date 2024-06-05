<?php

namespace App\Http\Resources\Tasks;

use App\Http\Resources\Resource;

class MemberTaskResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->merge(collect($this->task)->except('id'))
            ->except('task', 'user_id', 'task_id', 'category_id', 'company_id', 'is_active', 'created_by_id', 'updated_by_id', 'time', 'created_at', 'updated_at')
            ->put('category', collect($this->task->category)->only('id', 'name'))
            ->put('thumbnail', $this->task->thumbnailUrl)
            ->put('media', $this->task->getMedia);
    }
}
