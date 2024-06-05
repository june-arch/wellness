<?php

namespace App\Http\Collections\Users;

use App\Http\Collections\Collection;

class UserCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->except(
                    'file',
                    'company_id',
                    'created_by_id',
                    'updated_by_id',
                    'branch_id',
                    'division_id',
                    'role_id',
                    'bio',
                )
                ->put('thumbnail', $item->thumbnailUrl)
                ->merge($item->dataLog());
        });

        return parent::toArray($request);
    }
}
