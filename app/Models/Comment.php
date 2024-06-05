<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Traits\HasCreator;
use App\Models\Traits\HasLog;
use App\Models\Traits\HasParent;
use App\Models\Users\Admin;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    use HasCreator;
    use HasParent;
    use HasLog;

    public $keywordField = 'content';

    public $fillable = [
        'name',
        'rating',
        'content',
        'parent_id',
        'commentable_id',
        'commentable_type',
        'type',
        'approved_by_id',
        'approved_at',
        'created_by_id',
        'updated_by_id',
        'votes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function isApproved()
    {
        if (!$this->approved_at) {
            abort(404);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by_id');
    }

    public function replied()
    {
        return $this->belongsTo(self::class, 'replied_id');
    }

    public function scopeApproved($query, $parent = null)
    {
        $query = $query->whereNotNull('approved_at')->latest('updated_at');

        return $query->where('parent_id', $parent->id ?? null);
    }
}
