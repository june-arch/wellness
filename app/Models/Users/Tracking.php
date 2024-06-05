<?php

namespace App\Models\Users;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tracking extends Model
{
    use HasFactory;

    public $fillable = ['user_id', 'lat', 'long'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
