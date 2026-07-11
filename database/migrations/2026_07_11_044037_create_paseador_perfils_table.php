<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paseadores_perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('identificacion', 20)->unique();
            $table->integer('experiencia_meses')->default(0);
            $table->decimal('calificacion_promedio', 3, 2)->default(5.00);
            $table->enum('estado', ['pendiente', 'activo', 'rechazado'])->default('pendiente');
            $table->string('documento_soporte', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paseador_perfils');
    }
};
