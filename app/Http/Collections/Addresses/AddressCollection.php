<?php

namespace App\Http\Collections\Addresses;

use App\Http\Collections\Collection;
use App\Http\Resources\Addresses\AddressResource;

class AddressCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            return new AddressResource($item);
        });

        return parent::toArray($request);
    }
}
