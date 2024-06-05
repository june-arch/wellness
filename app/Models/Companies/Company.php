<?php

namespace App\Models\Companies;

use App\Models\Media\HasFile;
use App\Models\Model;
use App\Models\Traits\HasCreator;
use App\Models\Users\Admin;
use App\Models\Users\Member;

class Company extends Model
{
    use HasFile, HasCreator;

    public $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'address',
        'is_active',
        'expires_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function healthThresholds()
    {
        return $this->hasMany(HealthThreshold::class);
    }

    public function getStatusAttribute()
    {
        return $this->is_active && now()->lessThanOrEqualTo($this->expires_at);
    }
}
