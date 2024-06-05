<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Typeable
{
    protected static function booted()
    {
        parent::booted();

        $user = auth()->guard('admin')->user();

        static::addGlobalScope('company', function (Builder $builder) use ($user) {
            if ($user && $user->company_id) {
                $builder->where('company_id', $user->company_id);
            }

            $builder->whereType(self::class);
        });

        static::creating(function ($model) use ($user) {
            $model->type = self::class;

            if ($user && $user->company_id) {
                $model->company_id = $user->company_id;
            }
        });
    }
}
