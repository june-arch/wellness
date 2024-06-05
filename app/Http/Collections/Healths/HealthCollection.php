<?php

namespace App\Http\Collections\Healths;

use App\Http\Collections\Collection;

class HealthCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(fn($item) =>
            collect($item)
                ->only('date', 'fitness')
                ->merge($item->pointData)
                ->put(
                    'heart_rate',
                    !isset($item['heart_rate']) || $item['heart_rate'] == 0 ? [] : $item['heart_rate']
                )
        );

        return parent::toArray($request);
    }
}
