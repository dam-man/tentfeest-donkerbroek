<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPasswordResetNotification extends Notification
{
    use Queueable;

	private string $token = '';
    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
       $this->token = $token;
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
	    return (new MailMessage)
		    ->subject('Tentfeest Donkerbroek - Wachtwoord Herstellen')
		    ->greeting('Hey ' . $notifiable->name . '!')
		    ->line('We hebben het verzoek gekregen om je wachtwoord te resetten. Klik op de onderstaande knop om je wachtwoord te herstellen.')
		    ->action('Wachtwoord Herstellen', url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false)))
		    ->line('Heb je deze mail niet zelf aangevraagd? Dan kan je deze mail negeren en gewoon weg gooien!');
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
