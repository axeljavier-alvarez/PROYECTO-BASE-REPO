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
        Schema::create('dependientes_constancias', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 45);
            $table->string('apellidos', 45);
            $table->foreignId('detalle_solicitud_id')
            ->constrained('detalles_solicitudes')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependientes_constancias');
    }
};
