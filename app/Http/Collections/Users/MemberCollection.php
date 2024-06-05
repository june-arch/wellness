<?php

namespace App\Http\Collections\Users;

use App\Http\Collections\Collection;

class MemberCollection extends Collection
{
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($item) {
            $data = collect($item)
                ->put('thumbnail', $item->thumbnailUrl)
                ->except(
                    'file',
                    'parent_id',
                    'company_id',
                    'role_id',
                    'created_by_id',
                    'updated_by_id',
                    'bio',
                )
                ->merge($item->dataLog());

            if ($item->supervisor) {
                $data = $data->put('supervisor', collect($item->supervisor)->put('thumbnail', $item->supervisor->thumbnailUrl));
            }

            return $data;
        });

        return parent::toArray($request);
    }
}
