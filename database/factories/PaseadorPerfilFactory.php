<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaseadorPerfil>
 */
class PaseadorPerfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'identificacion' => fake()->unique()->numerify('##########'),
            'experiencia_meses' => fake()->numberBetween(1, 60),
            'calificacion_promedio' => fake()->randomFloat(2, 4, 5),
            'estado' => 'activo', // Los de prueba los crearemos ya activos
            'documento_soporte' => 'soportes/cedula_fake.pdf',
        ];
    }
}
