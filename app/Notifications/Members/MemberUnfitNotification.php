<?php

namespace App\Notifications\Members;

use App\Models\Users\Member;
use App\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MemberUnfitNotification extends Notification
{
    public $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Halo {$notifiable->name},")
            ->line("Kami ingin menginforamsikan jika kesehatan Anda menurun. Mohon jaga kesehatan Anda.")
            ->line('Terima kasih untuk perhatiannya dan semoga Anda sehat selalu.');
    }
}
