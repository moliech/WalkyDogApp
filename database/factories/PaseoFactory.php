<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Mascota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaseoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'paseador_id' => User::factory(),
            'mascota_id' => Mascota::factory(),
            'estado' => fake()->randomElement(['programado', 'en_progreso', 'finalizado']),
            'token_qr' => 'walkydog_qr_' . Str::random(16),
            'hora_inicio' => fake()->optional()->dateTimeThisMonth(),
            'hora_fin' => fake()->optional()->dateTimeThisMonth(),
            'calificacion' => fake()->optional()->randomFloat(2, 3, 5),
        ];
    }
}