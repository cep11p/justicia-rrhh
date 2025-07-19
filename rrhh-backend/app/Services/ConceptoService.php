<?php

namespace App\Services;

use App\Models\Empleado;

class ConceptoService
{

    /**
     * Calcula el salario básico para un empleado en un período específico.
     *
     * Este método obtiene todas las designaciones del empleado para el período
     * y calcula el importe básico correspondiente a cada una, considerando
     * los días trabajados y el valor del concepto de salario básico.
     *
     * @param Empleado $empleado El empleado para calcular el básico
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return array Array con los importes básicos calculados por designación
     *
     * @example
     * $empleado = Empleado::find(1);
     * $basicos = $conceptoService->calcularBasico($empleado, '202401');
     * // Retorna: [
     * //   [
     * //     'estructura_organizacional' => 'Administración',
     * //     'cargo' => 'Administrativo',
     * //     'periodo_fecha_inicio' => Carbon('2024-01-01'),
     * //     'periodo_fecha_fin' => Carbon('2024-01-31'),
     * //     'importe' => 1000.00
     * //   ]
     * // ]
     */
    public function calcularBasico(Empleado $empleado, string $periodo): array
    {
        // Obtener las designaciones del empleado para el período
        $designaciones = $empleado->getDesignacionesParaPeriodo($periodo);

        if ($designaciones->isEmpty()) {
            return [];
        }

        // Calcular los importes básicos para cada designación
        $resultado = $empleado->calcularImporteDeDesignaciones($designaciones, $periodo);

        return $resultado;
    }

}
