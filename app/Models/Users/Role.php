<?php

namespace App\Models\Users;

use App\Models\Media\HasFile;
use App\Models\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\HasCreator;
use App\Models\Traits\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    use HasCreator;
    use HasLog;
    use HasFile;
    use BelongsToCompany;

    public $keywordField = 'name';

    public $fillable = ['company_id', 'name', 'description', 'parent', 'created_by', 'updated_by'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }
}
