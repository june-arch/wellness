<?php

namespace App\Notifications\Messeges;

use App\Models\Messege;
use App\Notifications\Notification;

class MessegeNotification extends Notification
{
    public $messege;

    public function __construct(Messege $messege)
    {
        $this->messege = $messege;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}
