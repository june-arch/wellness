<?php

namespace App\Http\Resources\Tasks;

use App\Http\Resources\Resource;

class TaskTagResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->only('id', 'name')
            ->merge($this->dataLog());
    }
}
