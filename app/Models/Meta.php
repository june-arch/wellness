<?php

namespace App\Models;

class Meta extends Model
{

    protected $fillable = [
        'metaablee_id',
        'metaable_type',
        'meta_key',
        'meta_value',
    ];

    public function metable()
    {
        return $this->morphTo();
    }
}
