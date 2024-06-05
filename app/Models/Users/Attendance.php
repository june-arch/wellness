<?php

namespace App\Models\Users;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    public $fillable = ['user_id', 'status', 'memo', 'date'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
