<?php

namespace App\Models\Users;

use App\Models\Addresses\Address;
use App\Models\Companies\Company;
use App\Models\Media\HasFile;
use App\Models\Traits\HasCreator;
use App\Models\Traits\HasLog;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Notifiable;
    use HasFactory;
    use HasCreator;
    use HasLog;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasFile;
    use HasApiTokens;

    public $keywordField = 'name';

    public $table = 'users';

    public $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'api_token',
        'password',
        'bio',
        'gender',
        'birthdate',
        'is_active',
        'role_id',
        'email_verified_at',
        'remember_token',
        'parent',
        'type',
        'created_by_id',
        'updated_by_id',
    ];

    protected $hidden = [
        'password',
        'api_token',
        'email_verified_at',
        'remember_token',
        'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birthdate' => 'date',
    ];

    public function typeable()
    {
        return $this->morphTo(null, 'type', 'id');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    public function can($abilities, $arguments = [])
    {
        $abilities = collect($abilities)->map(function ($item) {
            return str($item)->replace('-', ' ')->snake()->upper();
        });

        return $this->role->permissions->whereIn('gate', $abilities ?? [])->count() > 0;
    }

    public function cant($abilities, $arguments = [])
    {
        return $this->role->permissions->whereNotIn('gate', $abilities ?? [])->count() > 0;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function subscribtions()
    {
        return $this->morphToMany(Subscribtion::class, 'subscribtionable');
    }

    public function passwordReset()
    {
        return $this->morphOne(PasswordReset::class, 'resetable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
