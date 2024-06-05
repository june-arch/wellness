<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Resource;

class UserResource extends Resource
{
    public function toArray($request)
    {
        $role = $this->role;

        $data = collect(parent::toArray($request))
            ->only('id', 'name', 'email', 'phone', 'bio', 'birtdate', 'is_active')
            ->put('thumbnail', $this->thumbnailUrl)
            ->put('role', collect($role)->only('id', 'name'))
            ->merge($this->dataLog());

        if ($this->company) {
            $data->put('company', collect($this->company)->only('id', 'name', 'thumbnail')->put('is_active', $this->company->status));
        }

        if ($role->permissions) {
            $data->put('permissions', $role->permissions->pluck('gate')->sort());
        }

        return $data;
    }
}
