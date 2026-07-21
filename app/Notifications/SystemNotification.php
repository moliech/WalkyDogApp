<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    protected string $mensaje;
    protected string $tipo;
    protected string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $mensaje, string $tipo, string $url = '#')
    {
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mensaje' => $this->mensaje,
            'tipo' => $this->tipo,
            'url' => $this->url,
        ];
    }
}
