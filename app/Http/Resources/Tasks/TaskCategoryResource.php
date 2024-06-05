<?php

namespace App\Http\Resources\Tasks;

use App\Http\Resources\Resource;

class TaskCategoryResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->only('id', 'name', 'description')
            ->merge($this->dataLog());
    }
}
