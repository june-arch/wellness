<?php

namespace App\Models\Users;

use App\Models\Model;

class Permission extends Model
{
    public $keywordField = 'gate';

    public $timestamps = false;

    public $fillable = ['gate', 'description'];

    public function admins()
    {
        return $this->morphedByMany(Admin::class, 'permissionable');
    }

    public function roles()
    {
        return $this->morphedByMany(Role::class, 'permissionable');
    }
}
