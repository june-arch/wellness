<?php

namespace App\Models\Tasks;

use App\Models\Media\HasFile;
use App\Models\Model;
use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskTag extends Model
{
    use HasFile, HasFactory, BelongsToCompany;

    public $fillable = [
        'name',
        'company_id',
        'created_by',
        'updated_by',
    ];

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'task_taggable');
    }
}
