<?php

namespace App\Http\Collections\Companies;

use App\Http\Collections\Collection;

class HealthThresholdCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->except('created_by_id', 'updated_by_id');
        });

        return parent::toArray($request);
    }
}
