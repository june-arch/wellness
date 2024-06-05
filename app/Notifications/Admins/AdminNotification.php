<?php

namespace App\Notifications\Admins;

use App\Models\Users\Admin;
use App\Notifications\Notification;

class AdminNotification extends Notification
{
    public $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}
