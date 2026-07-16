<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    // Inyectamos el servicio en el constructor
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Renderiza la vista con las publicaciones de la API externa.
     */
    public function index()
    {
        $posts = $this->postService->getAll();

        // Si la conexión falló o retornó nulo, mandamos un mensaje de error controlado
        $error = is_null($posts) ? 'No se pudo establecer conexión con el servidor de la API externa.' : null;
        $posts = $posts ?? [];

        return view('posts.index', compact('posts', 'error'));
    }
}