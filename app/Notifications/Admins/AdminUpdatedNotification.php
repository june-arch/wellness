<?php

namespace App\Notifications\Admins;

use Illuminate\Notifications\Messages\MailMessage;

class AdminUpdatedNotification extends AdminNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun Administrator Mengalami Perubahan')
            ->greeting("Hai {$notifiable->name},")
            ->line('Apakah Anda melakukan perubahan pada akun administrator ' . config('app.name') . ' Anda? Jika tidak silahkan login dan ganti password Anda untuk keamanan. Silahkan login melalu link di bawah ini.')
            ->action('Login', config('client.admin.login'))
            ->line('Terima Kasih.');
    }
}
