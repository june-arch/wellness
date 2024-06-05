<?php

namespace App\Notifications\Members;

use App\Models\Users\Member;
use App\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MemberUnfitForHRDNotification extends Notification
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
            ->line("Salah satu karyawan Anda dengan nama {$this->member->name} sedang tidak fit. Anda bisa melihat detailnya melalui link dibawah ini")
            ->action('Cek Status Kesehatan', config('app.url') . "/member/{$this->member->id}")
            ->line('Terima kasih untuk perhatiannya dan semoga Anda sehat selalu.');
    }

    public function toArray($notifiable)
    {
        return [
            'member_id'   => $this->member->id,
            'member_name' => $this->member->name,
            'fitness'     => $this->member->healts()->latest('date')->first()->fitness,
            'message'     => "Salah satu karyawan Anda dengan nama {$this->member->name} sedang tidak fit. Anda bisa melihat detailnya melalui link dibawah ini",
            'date'        => now()->format('Y-m-d H:i:s'),
        ];
    }
}
