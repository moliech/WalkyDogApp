<?php

namespace App\Policies;

use App\Models\Mascota;
use App\Models\User;

class MascotaPolicy
{
    /**
     * Determina si el usuario puede listar mascotas.
     */
    public function viewAny(User $user): bool
    {
        // Solo propietarios pueden listar mascotas (los admin/paseadores tienen sus propias vistas de auditoría)
        return $user->rol === 'propietario';
    }

    /**
     * Determina si el usuario puede ver los detalles de una mascota.
     */
    public function view(User $user, Mascota $mascota): bool
    {
        // El administrador y el dueño de la mascota pueden verla
        return $user->rol === 'admin' || $user->id === $mascota->propietario_id;
    }

    /**
     * Determina si el usuario puede registrar mascotas.
     */
    public function create(User $user): bool
    {
        // Solo propietarios pueden registrar mascotas
        return $user->rol === 'propietario';
    }

    /**
     * Determina si el usuario puede actualizar una mascota.
     */
    public function update(User $user, Mascota $mascota): bool
    {
        // Únicamente el dueño de la mascota puede editarla
        return $user->id === $mascota->propietario_id;
    }

    /**
     * Determina si el usuario puede eliminar una mascota.
     */
    public function delete(User $user, Mascota $mascota): bool
    {
        // Únicamente el dueño de la mascota puede borrarla
        return $user->id === $mascota->propietario_id;
    }
}