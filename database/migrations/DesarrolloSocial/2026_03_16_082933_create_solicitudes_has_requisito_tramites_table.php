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
            Schema::create('solicitudes_has_requisito_tramites', function (Blueprint $table) {
                $table->foreignId('solicitud_constancias_id')
                    // Explicitly name the constraint to keep it under 64 chars
                    ->constrained('solicitud_constancias', 'id', 'solic_req_solic_id_fk') 
                    ->onDelete('cascade');

                $table->foreignId('requisito_tramite_constancias_id')
                    // Do the same here for consistency
                    ->constrained('requisito_tramite_constancias', 'id', 'solic_req_tram_id_fk')
                    ->onDelete('cascade');

                $table->primary(['solicitud_constancias_id', 'requisito_tramite_constancias_id'], 'solic_req_primary');
                $table->timestamps();
            });
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_has_requisito_tramites');
    }
};
