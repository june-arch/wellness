<?php

namespace App\Http\Collections\Companies;

use App\Http\Collections\Collection;

class CompanyCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->except('description', 'type', 'city_id', 'created_by_id', 'updated_by_id')
                ->put('thumbnail', $item->thumbnailUrl);
        });

        return parent::toArray($request);
    }
}
