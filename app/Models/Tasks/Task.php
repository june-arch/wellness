<?php

namespace App\Models\Tasks;

use App\Models\Media\HasFile;
use App\Models\Model;
use App\Models\Tasks\TaskCategory;
use App\Models\Tasks\TaskTag;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFile, HasFactory, BelongsToCompany, HasCreator;

    public $fillable = [
        'name',
        'description',
        'is_active',
        'company_id',
        'category_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->morphToMany(TaskTag::class, 'task_taggable');
    }

    public function userTasks()
    {
        return $this->hasMany(UserTask::class, 'task_id');
    }
}
