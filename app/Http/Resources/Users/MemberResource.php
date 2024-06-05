<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Resource;

class MemberResource extends Resource
{
    public function toArray($request)
    {
        $data = collect(parent::toArray($request))
            ->only('id', 'name', 'email', 'phone', 'birtdate', 'is_active', 'bio')
            ->put('thumbnail', $this->thumbnailUrl);

        if ($this->company) {
            $data->put('company', collect($this->company)->only('id', 'name', 'thumbnail')->put('is_active', $this->company->status));
        }

        return $data;
    }
}
