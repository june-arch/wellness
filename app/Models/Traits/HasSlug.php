<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected function slugExists($slug)
    {
        $exists = self::where('id', '!=', $this->id)->whereSlug($slug);
        if ($this->type) {
            $exists = $exists->whereType($this->type);
        }

        if ($exists->first()) {
            $arrSlug = collect(explode('-', $slug));
            $last = $arrSlug->last();

            if (is_numeric($last)) {
                $arrSlug->pop();
                $arrSlug->push($last + 1);
            } else {
                $arrSlug->push(1);
            }

            return $this->slugExists($arrSlug->implode('-'));
        }

        return $slug;
    }

    public function createSlug()
    {
        return $this->slugExists(Str::slug($this->slug ?? $this->name));
    }
}
