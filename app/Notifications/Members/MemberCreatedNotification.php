<?php

namespace App\Notifications\Members;

use App\Models\Users\Member;
use App\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MemberCreatedNotification extends Notification
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
            ->line("Anda terdaftar pada aplikasi pantau kesehatan dari perusahaan {$notifiable->company->name}.")
            ->line("Silahkan login melalui url di bawah ini untuk melihat status kesehatan dan pembaruan data kesehatan Anda.")
            ->action('Login', config('app.url'))
            ->line('Terima kasih untuk perhatiannya dan semoga Anda sehat selalu.');
    }
}
