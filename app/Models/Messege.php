<?php

namespace App\Models;

use App\Models\Users\Admin;
use App\Models\Users\Family;

class Messege extends Model
{
    public $fillable = [
        'name',
        'phone',
        'email',
        'content',
        'user_id',
        'parent_id',
        'is_read',
        'read_by_id',
        'is_replied',
        'replied_by_id',
        'read_at',
    ];

    protected $casts = [
        'read_at'    => 'date',
        'is_read'    => 'boolean',
        'is_replied' => 'boolean',
    ];

    public function from()
    {
        return $this->belongsTo(Family::class, 'user_id');
    }

    public function readBy()
    {
        return $this->belongsTo(Admin::class, 'read_by_id');
    }

    public function repliedBy()
    {
        return $this->belongsTo(Admin::class, 'replied_by_id');
    }
}
