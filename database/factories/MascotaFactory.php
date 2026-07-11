<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MascotaFactory extends Factory
{
    public function definition(): array
    {
        $razas = ['Golden Retriever', 'Pug', 'Pastor Alemán', 'French Poodle', 'Beagle', 'Siberian Husky', 'Chihuahua'];
        
        return [
            'propietario_id' => User::factory(), // Genera un usuario dueño automáticamente si no se especifica
            'nombre' => fake()->firstName(),
            'raza' => fake()->randomElement($razas),
            'tamano' => fake()->randomElement(['Pequeño', 'Mediano', 'Grande']),
            'observaciones' => fake()->optional()->sentence(),
        ];
    }
}