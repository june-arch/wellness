<?php

namespace App\Models\Chats;

use App\Models\Media\HasFile;
use App\Models\Model;

class ChatItem extends Model
{
    use HasFile;

    public $timestamps = false;

    public $fillable = ['message', 'user_id', 'chat_id', 'date'];

    protected $casts = [
        'date' => 'datetime:Y-m-d H:i:s',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
