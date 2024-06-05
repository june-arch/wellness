<?php

namespace App\Models\Chats;

use App\Models\Model;

class UserChat extends Model
{
    public $timestamps = false;
    public $primaryKey = null;

    public $fillable = ['chat_id', 'user_id', 'last_read_at', 'last_delete_at'];

    protected $casts = [
        'last_read_at'   => 'date',
        'last_delete_at' => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
