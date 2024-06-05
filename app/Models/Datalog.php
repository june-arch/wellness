<?php

namespace App\Models;

class Datalog extends Model
{
    public $fillable = [
        'loggable_id',
        'loggable_type',
        'url_origin',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }
}
