<?php

namespace App\Services;

use App\Models\Concepto;
use App\Models\Empleado;
use App\Models\Liquidacion;
use App\Models\LiquidacionConcepto;
use App\Models\LiquidacionEmpleado;
use Illuminate\Support\Facades\DB;

class LiquidacionService
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

    public function store(array $data)
    {
        // Iniciar una transacción
        return DB::transaction(function () use ($data) {
            // Chequear si ya existe una liquidación para ese empleado y ese periodo
            $liquidacion = Liquidacion::where('periodo', $data['periodo'] ?? null)
                ->whereHas('empleados', function($query) use ($data) {
                    if (isset($data['empleado_id'])) {
                        $query->where('empleado_id', $data['empleado_id']);
                    }
                })
                ->first();


            if ($liquidacion) {
                // Si ya existe una liquidación para ese empleado y ese periodo, notificar que existe
                throw new \Exception('Ya existe una liquidación para este empleado y período.');
            }

            // Crear una nueva liquidación y que el número empiece desde 1

            // Obtener el último número de liquidación existente
            $ultimoNumero = \App\Models\Liquidacion::max('numero');

            // Si no hay ninguna liquidación, empezar desde 1
            $nuevoNumero = $ultimoNumero ? $ultimoNumero + 1 : 1;

            $liquidacion = \App\Models\Liquidacion::create([
                'numero' => $nuevoNumero,
                'periodo' => $data['periodo'] ?? null,
                'fecha_liquidacion' => $data['fecha_liquidacion'] ?? now(),
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $empleado = Empleado::find($data['empleado_id']);

            $this->crearLiquidacionConceptoBasico($liquidacion, $empleado, $data['periodo']);

            return $liquidacion;
        });
    }

    public function crearLiquidacionConceptoBasico(Liquidacion $liquidacion, Empleado $empleado, string $periodo)
    {
        $colleccion_basicos = $empleado->getImportePorDesginacion($periodo);

        foreach ($colleccion_basicos as $basico) {

            $liquidacion_concepto_atributos = [
                'liquidacion_id' => $liquidacion->id,
                'concepto_id' => Concepto::where('codigo', '001')->first()->id,
                'importe' => $basico['importe'],
            ];

            LiquidacionConcepto::create($liquidacion_concepto_atributos);
        }
    }

}
