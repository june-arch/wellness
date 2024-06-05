<?php

namespace App\Models\Tasks;

use App\Models\Media\HasFile;
use App\Models\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskCategory extends Model
{
    use HasFile, HasFactory, BelongsToCompany, HasCreator;

    public $fillable = [
        'name',
        'description',
        'company_id',
        'created_by_id',
        'updated_by_id',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'category_id');
    }
}
