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
        Schema::create('requisito_tramite_constancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisito_constancia_id')
                ->constrained('requisito_constancias')
                ->cascadeOnDelete();

            $table->foreignId('tramite_constancia_id')
                ->constrained('tramite_constancias')
                ->cascadeOnDelete();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisito_tramite_constancias');
    }
};
