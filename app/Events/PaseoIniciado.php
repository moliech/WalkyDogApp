<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\Paseo;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaseoIniciado
{
    use Dispatchable, SerializesModels;

    public Paseo $paseo;

    /**
     * Create a new event instance.
     */
    public function __construct(Paseo $paseo)
    {
        $this->paseo = $paseo;
    }
}
