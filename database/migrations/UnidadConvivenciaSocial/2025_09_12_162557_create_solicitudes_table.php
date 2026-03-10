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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('primer_nombre',50);
            $table->string('segundo_nombre',50)->nullable();
            $table->string('primer_apellido',50);
            $table->string('segundo_apellido',50)->nullable();
            $table->string('cui', 13);
            $table->string('nit', 15);
            $table->string('patente_comercio');
            $table->string('telefono', 8);
            $table->string('correo', 100);
            $table->foreignId('zona_id')->nullable()->constrained();  
            $table->string('colonia',100)->nullable();
            $table->string('domicilio', 500);
            $table->text('actividad_negocio');
            $table->decimal('largo', 8, 2);
            $table->decimal('ancho', 8, 2);
            $table->text('observaciones')->nullable();
            $table->foreignId('sede_id')->constrained();
            $table->enum('tipo_persona',['Individual','Juridica']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
