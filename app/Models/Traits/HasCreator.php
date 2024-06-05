<?php

namespace App\Models\Traits;

use App\Models\Users\User;

trait HasCreator
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function dataLog()
    {
        $system = [
            'id'   => 0,
            'name' => 'System',
        ];

        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => isset($this->createdBy) ? $this->createdBy->only('id', 'name') : $system,
            'updated_by' => isset($this->updatedBy) ? $this->updatedBy->only('id', 'name') : $system,
        ];
    }
}
