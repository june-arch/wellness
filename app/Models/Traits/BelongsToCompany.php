<?php

namespace App\Models\Traits;

use App\Models\Companies\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    protected static function booted()
    {
        parent::booted();

        $user = Auth::user();

        static::addGlobalScope('copmany', function (Builder $builder) use ($user) {
            if ($user && $user->company_id) {
                $builder->where('company_id', null)->orWhere('company_id', $user->company_id);
            }
        });

        static::creating(function ($model) use ($user) {
            if ($user && $user->company_id) {
                $model->company_id = $user->company_id;
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
