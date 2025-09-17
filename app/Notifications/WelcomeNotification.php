<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // You may pass dependencies or data if needed
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
            ->subject('¡Bienvenido a Chinmex!')
            ->greeting('Hola '.$notifiable->name.'!')
            ->line('Gracias por registrarte en Chinmex. Tu cuenta ha sido creada exitosamente.')
            ->line('Ya puedes iniciar sesión y comenzar a usar la plataforma.')
            ->salutation('Saludos, equipo Chinmex');
    }
}
