<?php

namespace App\Http\Collections\Healths;

use App\Http\Collections\Collection;

class HealthUserCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)->put('bmi', $item->bmi);
        });

        return parent::toArray($request);
    }
}
