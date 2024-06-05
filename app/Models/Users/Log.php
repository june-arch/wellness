<?php

namespace App\Models\Users;

use App\Models\Model;
use App\Models\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;
    use HasCreator;

    public $keywordField = 'data';

    public $fillable = [
        'user_id',
        'data',
        'method',
        'path',
        'ip_address',
        'logable_id',
        'logable_type',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function logable()
    {
        return $this->morphTo();
    }
}
