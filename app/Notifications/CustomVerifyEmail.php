<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email Anda - Mutiara Nasional Line')
            ->greeting('Halo ' . $notifiable->first_name . '!')
            ->line('Terima kasih telah mendaftar di Mutiara Nasional Line.')
            ->line('Untuk menyelesaikan pendaftaran Anda, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Link verifikasi ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak membuat akun, tidak ada tindakan lebih lanjut yang diperlukan.')
            ->salutation('Salam hormat,')
            ->salutation('Tim Mutiara Nasional Line');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
