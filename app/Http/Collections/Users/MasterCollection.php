<?php

namespace App\Http\Collections\Users;

use App\Http\Collections\Collection;

class MasterCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->only('id', 'name')
                ->put('bio', substr($item->bio, 0, 100))
                ->put('thumbnail', $item->thumbnailUrl);
        });

        return parent::toArray($request);
    }
}
