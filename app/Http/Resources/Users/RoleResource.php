<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Resource;

class RoleResource extends Resource
{
    public function toArray($request)
    {
        $data = collect(parent::toArray($request))
            ->only('id', 'name', 'description')
            ->merge($this->dataLog());

        return $data;
    }
}
