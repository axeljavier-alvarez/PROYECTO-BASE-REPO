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
        Schema::create('solicitudes_constancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_constancia_id')->constrained('zonas_constancias')->onDelete('cascade');
            $table->foreignId('estado_constancia_id')->constrained('estados_constancias')->onDelete('cascade');
            $table->string('no_solicitud', 15)->nullable();
            $table->integer('anio')->length(4);
            $table->string('nombres', 60);
            $table->string('apellidos', 60);
            $table->string('email', 45);
            $table->string('telefono', 20);
            $table->string('cui', 13);
            $table->string('domicilio', 255);
            $table->string('observaciones', 500)->nullable(); 
            $table->string('razon', 255)->nullable();
            $table->foreignId('tramite_constancia_id')->constrained('tramites_constancias')->restrictOnDelete(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_constancias');
    }
};
