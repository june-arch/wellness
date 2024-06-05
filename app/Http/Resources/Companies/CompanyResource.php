<?php

namespace App\Http\Resources\Companies;

use App\Http\Resources\Resource;

class CompanyResource extends Resource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))
            ->except('created_by_id', 'updated_by_id', 'type', 'city_id')
            ->put('status', $this->status ? 'ACTIVE' : 'INACTIVE')
            ->put('thumbnail', $this->thumbnailUrl)
            ->put('health_thresholds', $this->healthThresholds
                    ->map(function ($item) {return $item->only('code', 'name', 'ratio', 'target');})
            )
            ->merge($this->dataLog());
    }
}
