<?php

namespace App\Http\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
    public function __construct($resource)
    {
        $request = request();

        $perPage = $request->per_page === 'all' ? $resource->count() : $request->per_page;
        $queries = $resource->paginate($perPage ?? 20)->appends($request->except('page'));

        parent::__construct($queries);
        $this->resource = $this->collectResource($queries);
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
