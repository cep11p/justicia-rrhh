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
        Schema::create('liquidacion_conceptos', function (Blueprint $table) {
            $table->id();
            $table->decimal('importe', 10, 2);
            $table->foreignId('liquidacion_empleado_id')->constrained('liquidacion_empleados')->onDelete('cascade');
            $table->foreignId('concepto_id')->constrained('conceptos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liquidacion_conceptos');
    }
};
