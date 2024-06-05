<?php

namespace App\Models\Users;

use App\Models\Healts\Health;
use App\Models\Traits\Typeable;

class Employee extends User
{
    use Typeable;

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }

    public function healths()
    {
        return $this->hasMany(Health::class, 'user_id');
    }

    public function locations()
    {
        return $this->hasMany(Tracking::class, 'user_id');
    }

    public function lastLocation()
    {
        return $this->locations()->latest()->first();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function todayAttendance()
    {
        return $this->attendances()->where('date', now()->format('Y-m-d'))->first();
    }
}
