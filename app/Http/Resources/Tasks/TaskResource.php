<?php

namespace App\Http\Resources\Tasks;

use App\Http\Resources\Resource;

class TaskResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->put('thumbnail', $this->thumbnailUrl)
            ->put('media', $this->getMedia)
            ->put('category', $this->category->only('id', 'name'))
            ->put('tags', $this->tags->map(function ($item) {return collect($item)->only('id', 'name');}));
    }
}
