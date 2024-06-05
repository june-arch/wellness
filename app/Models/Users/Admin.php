<?php

namespace App\Models\Users;

use App\Models\Chats\Chat;
use App\Models\Chats\UserChat;
use App\Models\Traits\Typeable;

class Admin extends User
{
    use Typeable;

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }

    public function chats()
    {
        return $this->hasManyThrough(Chat::class, UserChat::class, 'chat_id', 'user_id');
    }
}
