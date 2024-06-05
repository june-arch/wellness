<?php

namespace App\Models\Chats;

use App\Models\Model;
use App\Models\Users\Admin;
use App\Models\Users\Member;

class Chat extends Model
{
    public $fillable = ['date'];

    public function items()
    {
        return $this->hasMany(Member::class);
    }

    public function members()
    {
        return $this->hasManyThrough(Member::class, UserChat::class, 'user_id', 'chat_id');
    }

    public function admins()
    {
        return $this->hasManyThrough(Admin::class, UserChat::class, 'user_id', 'chat_id');
    }

    public function userChats()
    {
        return $this->hasMany(UserChat::class, 'chat_id');
    }
}
