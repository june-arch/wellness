<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function __construct($resource = [])
    {
        $this->resource = $resource;
    }

    public function toArray($request)
    {
        $data = collect($this->resource);

        return $data;
    }
}
