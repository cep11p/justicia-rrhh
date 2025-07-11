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
        Schema::create('valor_conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('periodo', 6); // YYYYMM
            $table->decimal('valor', 10, 2);
            $table->foreignId('concepto_id')->constrained('conceptos')->onDelete('cascade');
            $table->foreignId('cargo_id')->nullable()->constrained('cargos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valor_conceptos');
    }
};
