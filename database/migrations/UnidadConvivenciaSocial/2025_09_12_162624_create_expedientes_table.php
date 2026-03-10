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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->decimal('largo', 8, 2)->nullable();
            $table->decimal('ancho', 8, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('coordenadas')->nullable();
            $table->foreignId('area_sede_id')->nullable()->constrained('areas_sede');
            $table->foreignId('solicitud_id')->constrained('solicitudes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
