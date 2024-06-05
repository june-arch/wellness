<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait HasParent
{
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected function flattenWithChildren(): Collection
    {
        $collection = collect($this);

        if ($this->children->isNotEmpty()) {
            foreach ($this->children as $children) {
                $collection->merge($children->flattenWithChildren());
            }
        }

        return $collection;
    }

    public function getNestedChildren(array $with = []): self
    {
        if ($this->children->isNotEmpty()) {
            $this->children = $this->children->map(function (self $child) {
                return $child->getNestedChildren();
            });
        }

        foreach ($with as $w) {
            if (isset($this->{$w})) {
                $this->{$w} = $this->{$w} + $this->children->sum($w);
            }
        }

        return $this;
    }

    public function flattenWithParent(): Collection
    {
        $collection = collect([$this]);

        if ($this->parent) {
            $collection->merge($this->parent->flattenWithParent());
        }

        return $collection;
    }

    public function getNestedParent(): self
    {
        if ($this->parent) {
            $this->parent = $this->parent->getNestedParent();
        }

        return $this;
    }
}
