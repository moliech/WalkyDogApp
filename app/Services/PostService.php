<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PostService
{
    /**
     * Consume la API externa para obtener el listado de publicaciones.
     */
    public function getAll()
    {
        try {
            // Realizamos la petición HTTP GET con un límite de espera de 5 segundos
            $response = Http::timeout(5)->get('https://jsonplaceholder.typicode.com/posts');

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            // Si hay timeout o error de conexión de internet, capturamos el fallo de forma segura
            return null;
        }
    }
}