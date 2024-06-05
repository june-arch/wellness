<?php

namespace App\Http\Collections\Users;

use App\Http\Collections\Collection;

class RoleCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->only('id', 'name', 'created_at', 'updated_at');
        });

        return parent::toArray($request);
    }
}
