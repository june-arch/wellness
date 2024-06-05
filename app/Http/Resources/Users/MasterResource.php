<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Resource;

class MasterResource extends Resource
{
    public function toArray($request)
    {
        $data = collect(parent::toArray($request))
            ->only('id', 'name', 'bio')
            ->put('thumbnail', $this->thumbnailUrl);

        return $data;
    }
}
