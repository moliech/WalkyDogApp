<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Mascota;
use App\Models\User;

use Illuminate\Support\Facades\Event;
use App\Events\PaseoIniciado;
use App\Listeners\EnviarAlertaPaseoIniciado;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Evento y Listener de Alerta de Paseo Iniciado
        Event::listen(
            PaseoIniciado::class,
            EnviarAlertaPaseoIniciado::class
        );

        // Compartir las mascotas del usuario y los paseadores activos con el layout
        // Compartir las mascotas del usuario, paseadores activos y notificaciones con el layout
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('unreadNotifications', auth()->user()->unreadNotifications);
                
                if (!auth()->user()->isAdmin()) {
                    $view->with('myPets', Mascota::where('propietario_id', auth()->id())->get());
                    $view->with('activeWalkers', User::whereHas('perfilPaseador', function ($query) {
                        $query->where('estado', 'activo');
                    })->get());
                }
            }
        });
    }
}
