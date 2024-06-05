<?php

namespace App\Http\Collections\Chats;

use App\Http\Collections\Collection;

class ChatCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->only('id', 'message', 'date')
                ->put('media', $item->getMedia);
        })
            ->sortBy('id');

        return parent::toArray($request);
    }
}
