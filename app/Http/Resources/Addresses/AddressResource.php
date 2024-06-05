<?php

namespace App\Http\Resources\Addresses;

use App\Http\Resources\Resource;

class AddressResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->only('id', 'name', 'receiver', 'line_1', 'line_2', 'country', 'phone', 'phone_2', 'email', 'postcode')
            ->put('village', $this->village->name)
            ->put('district', $this->district->name)
            ->put('city', $this->city->name)
            ->put('province', $this->province->name)
        ;
    }
}
