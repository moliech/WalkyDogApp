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
        Schema::create('ajustes_tarifas', function (Blueprint $table) {
            $table->id();
            $table->decimal('calificacion_minima', 3, 2)->default(4.50);
            $table->integer('porcentaje_maximo')->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustes_tarifas');
    }
};
