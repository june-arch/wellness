<?php

namespace App\Http\Collections;

use App\Http\Resources\NotificationResource;

class NotificationCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)->mapInto(NotificationResource::class);
        });

        return parent::toArray($request);
    }
}
