<?php

namespace App\Models\Traits;

use App\Models\Users\Log;

trait HasLog
{
    public function logs()
    {
        return $this->morphMany(Log::class, 'logable');
    }
}
