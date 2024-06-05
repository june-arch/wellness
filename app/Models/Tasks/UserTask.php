<?php

namespace App\Models\Tasks;

use App\Models\Model;
use App\Models\Users\Member;

class UserTask extends Model
{
    public $timestamps = false;

    public $fillable = [
        'user_id',
        'task_id',
        'is_complete',
        'date',
        'time',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }
}
