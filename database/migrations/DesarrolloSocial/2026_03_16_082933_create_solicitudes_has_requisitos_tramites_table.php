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
    Schema::create('solicitudes_has_requisitos_tramites', function (Blueprint $table) {
        // 1. Definimos la columna (usando el singular preferiblemente para seguir tu nuevo estándar)
        $table->foreignId('solicitud_constancia_id')
            // Importante: El primer parámetro es el nombre real de tu tabla de solicitudes
            ->constrained('solicitudes_constancias', 'id', 'solic_req_solic_id_fk') 
            ->onDelete('cascade');

        // 2. Segunda llave foránea
        $table->foreignId('requisito_tramite_constancia_id')
            ->constrained('requisitos_tramites_constancias', 'id', 'solic_req_tram_id_fk')
            ->onDelete('cascade');

        // 3. El Primary Key debe usar los nombres EXACTOS de las columnas de arriba
        $table->primary(
            ['solicitud_constancia_id', 'requisito_tramite_constancia_id'], 
            'solic_req_primary'
        );
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_has_requisitos_tramites');
    }
};
