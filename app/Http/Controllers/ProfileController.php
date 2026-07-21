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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Imagen de máx 2MB
        ];
        // Validaciones condicionales si es un Paseador
        if ($user->perfilPaseador) {
            $rules['identificacion'] = ['required', 'string', 'max:20', 'unique:paseadores_perfiles,identificacion,' . $user->perfilPaseador->id];
            $rules['experiencia_meses'] = ['required', 'integer', 'min:0'];
            $rules['documento_soporte'] = ['nullable', 'file', 'mimes:pdf', 'max:2048']; // PDF de máx 2MB

            // Validamos recargo si cumple con el puntaje mínimo de destacados
            $ajustes = \App\Models\AjusteTarifa::first();
            $minCalificacion = $ajustes ? $ajustes->calificacion_minima : 4.5;
            $maxPorcentaje = $ajustes ? $ajustes->porcentaje_maximo : 20;

            if ($user->perfilPaseador->calificacion_promedio >= $minCalificacion) {
                $rules['porcentaje_recargo'] = ['required', 'integer', 'min:0', 'max:' . $maxPorcentaje];
            }
        }
        $validated = $request->validate($rules);

        // Subir avatar si se cargó una imagen nueva
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // Eliminamos la foto vieja si existía
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            // Guardamos la nueva foto
            $path = $file->store('avatars', 'public');
            $user->avatar = $path; // Asignamos la ruta correcta (ej. 'avatars/xyz.png')
        }

        // Actualizar datos de usuario
        $user->update([
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'username' => $validated['username'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'],
            'avatar' => $user->avatar,
        ]);



        // Actualizar datos del perfil de paseador si aplica
        if ($user->perfilPaseador) {
            $perfilData = [
                'identificacion' => $validated['identificacion'],
                'experiencia_meses' => $validated['experiencia_meses'],
            ];

            if (isset($validated['porcentaje_recargo'])) {
                $perfilData['porcentaje_recargo'] = $validated['porcentaje_recargo'];
            } else {
                $perfilData['porcentaje_recargo'] = 0;
            }
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

    public function postularse(Request $request)
    {
        $user = auth()->user();

        // Validar que no tenga ya una postulación activa o aprobada
        if ($user->perfilPaseador && $user->perfilPaseador->estado !== 'rechazado') {
            return back()->withErrors(['error' => 'Ya tienes una postulación en curso o tu perfil ya está activo.']);
        }

        $validated = $request->validate([
            'identificacion' => ['required', 'string', 'max:20', 'unique:paseadores_perfiles,identificacion'],
            'experiencia_meses' => ['required', 'integer', 'min:0'],
            'documento_soporte' => ['required', 'file', 'mimes:pdf', 'max:2048'], // PDF obligatorio para postularse
        ]);

        // Subir el documento
        $file = $request->file('documento_soporte');
        $path = $file->store('documentos_soporte', 'public');

        if ($user->perfilPaseador && $user->perfilPaseador->estado === 'rechazado') {
            // Si fue rechazado previamente, actualizamos su postulación anterior
            $user->perfilPaseador->update([
                'identificacion' => $validated['identificacion'],
                'experiencia_meses' => $validated['experiencia_meses'],
                'documento_soporte' => $path,
                'estado' => 'pendiente',
            ]);
        } else {
            // Crear nueva postulación
            \App\Models\PaseadorPerfil::create([
                'user_id' => $user->id,
                'identificacion' => $validated['identificacion'],
                'experiencia_meses' => $validated['experiencia_meses'],
                'documento_soporte' => $path,
                'estado' => 'pendiente',
                'calificacion_promedio' => 0.0,
                'porcentaje_recargo' => 0,
            ]);
        }

        // Notificar al usuario que se postula
        $user->notify(new \App\Notifications\SystemNotification(
            "Tu postulación para ser paseador ha sido recibida y está en revisión.",
            "postulacion_enviada",
            route('perfil.editar')
        ));

        // Notificar a todos los administradores
        $admins = \App\Models\User::where('rol', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SystemNotification(
                "Nueva postulación de paseador recibida: {$user->nombres} {$user->apellidos} ha solicitado ser paseador.",
                "nueva_postulacion",
                route('admin.paseadores')
            ));
        }

        return redirect()->route('perfil.editar')->with('success', '¡Tu postulación para ser paseador ha sido enviada con éxito! Está en espera de aprobación por el administrador.');
    }
}
