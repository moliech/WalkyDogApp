<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Registro de nuevo cliente.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:255',
        ]);

        $user = User::create([
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'],
        ]);

        // Login automático del usuario recién creado
        /** @var \Tymon\JWTAuth\JWTGuard $auth */
        $auth = auth('api');
        $token = $auth->login($user);

        return $this->respondWithToken($token);
    }

    /**
     * Inicio de sesión.
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        /** @var \Tymon\JWTAuth\JWTGuard $auth */
        $auth = auth('api');

        if (!$token = $auth->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Obtener el perfil del usuario autenticado.
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Cierre de sesión e invalidación del token.
     */
    public function logout()
    {
        /** @var \Tymon\JWTAuth\JWTGuard $auth */
        $auth = auth('api');
        $auth->logout();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Helper para dar formato de respuesta del token.
     */
    protected function respondWithToken($token)
    {
        /** @var \Tymon\JWTAuth\JWTGuard $auth */
        $auth = auth('api');
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60
        ]);
    }
}