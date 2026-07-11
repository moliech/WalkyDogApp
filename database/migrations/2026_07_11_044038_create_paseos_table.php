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
        Schema::create('paseos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paseador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
            $table->enum('estado', ['programado', 'en_progreso', 'finalizado'])->default('programado');
            $table->string('token_qr', 255)->unique();
            $table->timestamp('hora_inicio')->nullable();
            $table->timestamp('hora_fin')->nullable();
            $table->decimal('calificacion', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paseos');
    }
};
