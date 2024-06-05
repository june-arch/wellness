<?php

namespace App\Http\Collections\Tasks;

use App\Http\Collections\Collection;

class TaskTagCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            $data = collect($item)
                ->except(
                    'created_by_id',
                    'updated_by_id',
                    'description',
                    'company_id'
                )
                ->merge($item->dataLog());

            return $data;
        });

        return parent::toArray($request);
    }
}
