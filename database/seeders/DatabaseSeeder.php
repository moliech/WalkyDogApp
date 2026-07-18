<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mascota;
use App\Models\PaseadorPerfil;
use App\Models\Paseo;
use App\Models\Pago;
use App\Models\Ubicacion;
use App\Models\Novedad;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Crear los Tamaños y Tarifas Paramétricas
        \App\Models\MascotaTamano::create(['nombre' => 'Pequeño', 'tarifa_por_hora' => 10000]);
        \App\Models\MascotaTamano::create(['nombre' => 'Mediano', 'tarifa_por_hora' => 12000]);
        \App\Models\MascotaTamano::create(['nombre' => 'Grande', 'tarifa_por_hora' => 15000]);
        \App\Models\AjusteTarifa::create(['calificacion_minima' => 4.50, 'porcentaje_maximo' => 20]);

        // 1. Crear tu Usuario Propietario de Prueba
        $propietarioTest = User::create([
            'nombres' => 'Jhon Esteban',
            'apellidos' => 'Molina',
            'email' => 'esteban.molina@cotecnova.edu.co',
            'password' => bcrypt('password'), // La contraseña de prueba será 'password'
            'telefono' => '3123456789',
            'direccion' => 'Calle 10 # 4-50, Cartago, Valle',
        ]);

        // 2. Crear las Mascotas para tu usuario de prueba
        $toby = Mascota::create([
            'propietario_id' => $propietarioTest->id,
            'nombre' => 'Toby',
            'raza' => 'Golden Retriever',
            'tamano' => 'Grande',
            'observaciones' => 'Muy juguetón y amigable.',
        ]);

        $luna = Mascota::create([
            'propietario_id' => $propietarioTest->id,
            'nombre' => 'Luna',
            'raza' => 'Pug',
            'tamano' => 'Pequeño',
            'observaciones' => 'Tiene asma, no correr demasiado.',
        ]);

        $rambo = Mascota::create([
            'propietario_id' => $propietarioTest->id,
            'nombre' => 'Rambo',
            'raza' => 'Pastor Alemán',
            'tamano' => 'Grande',
            'observaciones' => 'Perro de guardia, usar bozal.',
        ]);

        // 3. Crear un Paseador de Prueba (con su perfil)
        $paseadorTest = User::create([
            'nombres' => 'Carlos',
            'apellidos' => 'Mendoza',
            'email' => 'carlos@demo.com',
            'password' => bcrypt('password'),
            'telefono' => '3219876543',
            'direccion' => 'Carrera 5 # 12-30, Cartago, Valle',
        ]);

        PaseadorPerfil::create([
            'user_id' => $paseadorTest->id,
            'identificacion' => '111222333',
            'experiencia_meses' => 24,
            'calificacion_promedio' => 4.90,
            'estado' => 'activo',
            'documento_soporte' => 'soportes/cedula_carlos.pdf',
        ]);

        // 4. Crear un Paseo para tu perro Toby asignado a Carlos Mendoza
        $paseoActivo = Paseo::create([
            'paseador_id' => $paseadorTest->id,
            'mascota_id' => $toby->id,
            'estado' => 'en_progreso',
            'token_qr' => 'walkydog_qr_secure_token_active_101',
            'hora_inicio' => now()->subMinutes(30),
            'hora_fin' => null,
            'calificacion' => null,
        ]);

        // Coordenadas de prueba en Cartago (para simular el mapa)
        $coords = [
            ['lat' => 4.7508, 'lng' => -75.9122],
            ['lat' => 4.7515, 'lng' => -75.9130],
            ['lat' => 4.7525, 'lng' => -75.9135],
        ];
        foreach ($coords as $c) {
            Ubicacion::create([
                'paseo_id' => $paseoActivo->id,
                'latitud' => $c['lat'],
                'longitud' => $c['lng'],
            ]);
        }

        // Crear una novedad para este paseo
        Novedad::create([
            'paseo_id' => $paseoActivo->id,
            'detalle' => 'El perro hizo sus necesidades y se hidrató correctamente.',
        ]);

        // 5. Poblar otros 10 Propietarios genéricos con mascotas
        User::factory(10)->create()->each(function ($user) {
            Mascota::factory(rand(1, 2))->create([
                'propietario_id' => $user->id
            ]);
        });

        // 6. Poblar otros 5 Paseadores con perfiles
        User::factory(5)->create()->each(function ($user) {
            PaseadorPerfil::factory()->create([
                'user_id' => $user->id
            ]);
        });

        // 7. Generar algunos paseos adicionales e históricos con pagos
        $paseadores = User::has('perfilPaseador')->get();
        $mascotas = Mascota::all();

        for ($i = 0; $i < 10; $i++) {
            $p = Paseo::create([
                'paseador_id' => $paseadores->random()->id,
                'mascota_id' => $mascotas->random()->id,
                'estado' => 'finalizado',
                'token_qr' => 'walkydog_qr_hist_' . uniqid(),
                'hora_inicio' => now()->subDays(rand(1, 10))->subHours(rand(1, 5)),
                'hora_fin' => now()->subDays(rand(1, 10)),
                'calificacion' => rand(4, 5),
            ]);

            Pago::create([
                'paseo_id' => $p->id,
                'monto' => rand(12000, 36000),
                'estado_pago' => 'approved',
            ]);
        }
    }
}