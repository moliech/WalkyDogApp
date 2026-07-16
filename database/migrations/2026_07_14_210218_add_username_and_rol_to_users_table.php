<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('apellidos');
            $table->enum('rol', ['admin', 'paseador', 'propietario'])->default('propietario')->after('direccion');
        });

        // Poblamos los datos de los usuarios ya existentes
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $role = 'propietario';

            // Determinar si es administrador por el email
            if ($user->email === 'esteban.molina@cotecnova.edu.co' || str_contains($user->email, 'admin')) {
                $role = 'admin';
            } else {
                // Determinar si es paseador si tiene perfil registrado
                $tienePerfilPaseador = DB::table('paseadores_perfiles')->where('user_id', $user->id)->exists();
                if ($tienePerfilPaseador) {
                    $role = 'paseador';
                }
            }

            // Generamos un username provisional basado en el correo electrónico
            $baseUsername = strstr($user->email, '@', true);
            $username = $baseUsername;
            $counter = 1;
            
            // Bucle para evitar colisiones de username
            while (DB::table('users')->where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // Actualizamos el usuario directamente sin pasar por Eloquent (evitando protecciones de fillable)
            DB::table('users')->where('id', $user->id)->update([
                'rol' => $role,
                'username' => $username,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'rol']);
        });
    }
};