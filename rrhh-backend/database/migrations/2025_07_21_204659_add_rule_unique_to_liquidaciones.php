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
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->unique(['empleado_id', 'periodo', 'deleted_at'], 'unique_empleado_periodo_soft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropUnique('unique_empleado_periodo_soft');
        });
    }
};
