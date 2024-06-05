<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as AppNotification;

class Notification extends AppNotification implements ShouldQueue
{
    use Queueable;

    public $locale = 'id_ID';

    protected function getThumbImage(string $var): string
    {
        return '<img src="' . $this->$var->getSmallThumbnailUrl() . '" alt="' . config('app.name') . ' . ' . $this->$var->name . '"/>';
    }
}
