<?php

namespace App\Listeners;

use App\Events\PaseoIniciado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\AlertaPaseoIniciado;
use Illuminate\Support\Facades\Mail;

class EnviarAlertaPaseoIniciado
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaseoIniciado $event): void
    {
        $paseo = $event->paseo;
        // Se envía al propietario de la mascota vinculada al paseo
        if ($paseo->mascota && $paseo->mascota->propietario && $paseo->mascota->propietario->email) {
            Mail::to($paseo->mascota->propietario->email)->send(new AlertaPaseoIniciado($paseo));
        }
    }
}
