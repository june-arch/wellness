<?php

namespace App\Notifications\Messeges;

use Illuminate\Notifications\Messages\MailMessage;

class MessegeCreatedNotification extends MessegeNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Terima Kasih Telah Menghubungi Kami')
            ->greeting("Hai {$this->messege->name},")
            ->line("Terima kasih telah menghubungi kami. Mohon tunggu kami akan segera membalas pesan Anda.")
            ->line('Jika Anda tidak mengirim pesan tersebut, mohon abaikan pesan ini.')
            ->line('Terima Kasih atas perhatian Anda.');
    }
}
