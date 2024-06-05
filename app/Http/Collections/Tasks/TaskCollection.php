<?php

namespace App\Http\Collections\Tasks;

use App\Http\Collections\Collection;

class TaskCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->put('thumbnail', $item->thumbnailUrl)
                ->except(
                    'created_by_id',
                    'updated_by_id',
                    'description',
                )
                ->merge($item->dataLog());
        });

        return parent::toArray($request);
    }
}
