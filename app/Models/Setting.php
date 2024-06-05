<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Traits\HasCreator;

class Setting extends Model
{
    use HasCreator;

    public $fillable = ['key', 'value', 'created_by_id', 'updated_by_id'];

    protected $casts = [
        'value' => 'array',
        'key'   => 'string',
    ];
}
