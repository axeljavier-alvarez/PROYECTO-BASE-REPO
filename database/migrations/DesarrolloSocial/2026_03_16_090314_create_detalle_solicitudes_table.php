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
        Schema::create('detalle_solicitudes', function (Blueprint $table) {
            
         $table->id();
            $table->string('path')->nullable();
            $table->string('tipo', 100);
            $table->foreignId('solicitud_constancias_id')->constrained('solicitud_constancias')->onDelete('cascade');
            $table->foreignId('requisito_tramite_constancias_id')
            ->nullable()
            ->constrained('requisito_tramite_constancias')
            ->onDelete('cascade');
           
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_solicitudes');
    }
};
