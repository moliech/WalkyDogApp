<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Paseo;

class PaseoNotification extends Notification
{
    use Queueable;

    protected Paseo $paseo;
    protected string $mensaje;
    protected string $tipo;
    protected string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(Paseo $paseo, string $mensaje, string $tipo, string $url)
    {
        $this->paseo = $paseo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'paseo_id' => $this->paseo->id,
            'mensaje' => $this->mensaje,
            'tipo' => $this->tipo,
            'url' => $this->url,
        ];
    }
}
