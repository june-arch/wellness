<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;

class NotificationResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->only('id', 'type', 'data', 'read_at', 'created_at')
            ->put('type', Str::studly(class_basename($this->type)))
        ;
    }
}
