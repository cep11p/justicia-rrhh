<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // // Primero eliminamos la foreign key de liquidacion_conceptos
        Schema::table('liquidacion_conceptos', function (Blueprint $table) {
            $table->dropForeign(['liquidacion_empleado_id']);
            $table->dropColumn('liquidacion_empleado_id');
            $table->foreignId('liquidacion_id')->constrained('liquidaciones')->onDelete('cascade');
        });

        Schema::table('liquidacion_empleados', function (Blueprint $table) {
            $table->dropForeign(['empleado_id']);
            $table->dropColumn('empleado_id');
            $table->dropForeign(['liquidacion_id']);
            $table->dropColumn('liquidacion_id');
        });

        Schema::dropIfExists('liquidacion_empleados');

        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear la tabla si es necesario
        Schema::create('liquidacion_empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados');
            $table->foreignId('liquidacion_id')->constrained('liquidaciones');
            $table->timestamps();
        });

        // Restaurar la estructura anterior de liquidacion_conceptos
        Schema::table('liquidacion_conceptos', function (Blueprint $table) {
            $table->dropForeign(['liquidacion_id']);
            $table->dropColumn('liquidacion_id');
            $table->foreignId('liquidacion_empleado_id')->constrained('liquidacion_empleados');
        });

        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropForeign(['empleado_id']);
            $table->dropColumn('empleado_id');
        });
    }
};
