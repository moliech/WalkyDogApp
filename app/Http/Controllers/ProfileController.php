<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Actualiza la información del perfil personalizado de WalkyDog.
     */
    public function updateCustom(Request $request): RedirectResponse
    {
        $user = $request->user();
        // Validaciones base del usuario
        $rules = [
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
        ];
        // Validaciones condicionales si es un Paseador
        if ($user->perfilPaseador) {
            $rules['identificacion'] = ['required', 'string', 'max:20', 'unique:paseadores_perfiles,identificacion,' . $user->perfilPaseador->id];
            $rules['experiencia_meses'] = ['required', 'integer', 'min:0'];
            $rules['documento_soporte'] = ['nullable', 'file', 'mimes:pdf', 'max:2048']; // PDF de máx 2MB
        }
        $validated = $request->validate($rules);
        // Actualizar datos de usuario
        $user->update([
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'username' => $validated['username'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'],
        ]);
        // Actualizar datos del perfil de paseador si aplica
        if ($user->perfilPaseador) {
            $perfilData = [
                'identificacion' => $validated['identificacion'],
                'experiencia_meses' => $validated['experiencia_meses'],
            ];
            // Subir documento de soporte si se cargó un archivo nuevo
            if ($request->hasFile('documento_soporte')) {
                $file = $request->file('documento_soporte');
                
                // Lo guarda en storage/app/public/documentos_soporte
                $path = $file->store('documentos_soporte', 'public');
                $perfilData['documento_soporte'] = $path;
            }
            $user->perfilPaseador->update($perfilData);
        }
        return redirect()->route('perfil.editar')->with('success', '¡Perfil actualizado correctamente!');
    }
}
