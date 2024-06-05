<?php

namespace App\Models;

use App\Models\Media\HasFile;
use App\Models\Traits\HasCreator;
use App\Models\Traits\HasLog;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use HasCreator;
    use HasSlug;
    use HasLog;
    use HasFile;

    public $keywordField = 'name';

    public $fillable = [
        'name',
        'slug',
        'content',
        'category_id',
        'is_publish',
        'type',
        'created_by_id',
        'updated_by_id',
    ];

    public function scopePublised($query)
    {
        return $query->where('is_publish', true);
    }
}
