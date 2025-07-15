<?php

namespace Database\Seeders;

use App\Models\LiquidacionEmpleado;
use App\Models\Liquidacion;
use App\Models\Empleado;
use Illuminate\Database\Seeder;

class LiquidacionEmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $liquidacion = Liquidacion::where('periodo', '202412')->first();

        if (!$liquidacion) {
            return;
        }

        // Obtener todos los empleados
        $empleados = Empleado::all();

        foreach ($empleados as $empleado) {
            // Verificar que el empleado tenga designación en el período
            if ($empleado->getDesignacionesParaPeriodo('202412')->isNotEmpty()) {
                // Crear liquidación de empleado con valores iniciales
                // Los totales se calcularán después con los conceptos
                LiquidacionEmpleado::create([
                    'liquidacion_id' => $liquidacion->id,
                    'empleado_id' => $empleado->id,
                ]);
            }
        }
    }
}
