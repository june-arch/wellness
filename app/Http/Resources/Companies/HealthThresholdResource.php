<?php

namespace App\Http\Resources\Companies;

use App\Http\Resources\Resource;

class HealthThresholdResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->merge($this->dataLog())
            ->put('company', $this->company->only('id', 'name'))
            ->except('created_by_id', 'updated_by_id');
    }
}
