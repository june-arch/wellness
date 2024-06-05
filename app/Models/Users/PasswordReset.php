<?php

namespace App\Models\Users;

use App\Models\Model;

class PasswordReset extends Model
{
    public $fillable = ['resetable_id', 'resetable_type', 'created_at', 'expires_at'];

    public function user()
    {
        return $this->morphTo();
    }

    public function shopUrl(): string
    {
        return $this->clientUrl('password_reset', 'token');
    }
}
