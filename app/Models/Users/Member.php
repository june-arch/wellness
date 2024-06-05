<?php

namespace App\Models\Users;

use App\Models\Chats\Chat;
use App\Models\Chats\UserChat;
use App\Models\Companies\MemberTask;
use App\Models\Healts\Health;
use App\Models\Traits\Typeable;

class Member extends User
{
    use Typeable;

    public function healths()
    {
        return $this->hasMany(Health::class, 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany(MemberTask::class, 'user_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Admin::class, 'parent_id');
    }

    public function chats()
    {
        return $this->hasManyThrough(Chat::class, UserChat::class, 'chat_id', 'user_id');
    }
}
