<?php

namespace App\Http\Collections;

use Illuminate\Support\Str;

class LogableWithThumbnailCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)
                ->put(
                    'admin',
                    collect($item->admin)
                        ->only('id', 'name')
                        ->put('thumbnail', $item->admin->thumbnailUrl)
                )
                ->put(
                    'logable',
                    collect($item->logable)
                        ->only('id', 'name')
                )
                ->put('type', Str::camel(class_basename($item->logable)))
                ->except('logable_id');
        });

        return parent::toArray($request);
    }
}
