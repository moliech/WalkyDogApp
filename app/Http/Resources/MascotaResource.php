<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MascotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'raza' => $this->raza,
            'tamano' => $this->tamano,
            'observaciones' => $this->observaciones,
            'propietario' => $this->propietario ? [
                'id' => $this->propietario->id,
                'nombre_completo' => $this->propietario->nombres . ' ' . $this->propietario->apellidos,
                'email' => $this->propietario->email,
            ] : null,
            'registrado_el' => $this->created_at ? $this->created_at->format('d-m-Y H:i:s') : null,
        ];
    }
}
