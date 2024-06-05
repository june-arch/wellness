<?php

namespace App\Models\Traits;

use App\Models\Meta;

trait HasMeta
{
    public function metas()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }

    public function saveMeta($key, $value): void
    {
        $this->metas()->updateOrCreate(
            [
                'meta_key' => $key
            ],
            [
                'meta_value' => $this->setMetaValue($value)
            ]
        );
    }

    public function saveMetas(array $data)
    {
        $metas = collect();

        foreach ($data as $key => $value) {
            $metas->push(['meta_key' => $key, 'meta_value' => $this->setMetaValue($value)]);
        }

        return $this->metas()->saveMany($metas->mapInto(Meta::class));
    }

    protected function setMetaValue($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }
}
