<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Mascota;
use App\Models\User;

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
        // Compartir las mascotas del usuario y los paseadores activos con el layout
        View::composer('layouts.app', function ($view) {
            if (auth()->check() && !auth()->user()->isAdmin()) {
                $view->with('myPets', Mascota::where('propietario_id', auth()->id())->get());
                $view->with('activeWalkers', User::whereHas('perfilPaseador', function ($query) {
                    $query->where('estado', 'activo');
                })->get());
            }
        });
    }
}
