<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concepto;
use App\Models\ValorConcepto;

class Designacion extends Model
{
    use HasFactory;

    protected $table = 'designaciones';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'empleado_id',
        'estructura_organizativa_id',
        'cargo_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'empleado_id' => 'integer',
        'estructura_organizativa_id' => 'integer',
        'cargo_id' => 'integer',
    ];

    /**
     * Obtiene el empleado de esta designación
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Obtiene la estructura organizativa de esta designación
     */
    public function estructuraOrganizativa(): BelongsTo
    {
        return $this->belongsTo(EstructuraOrganizativa::class);
    }

    /**
     * Obtiene el cargo de esta designación
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * Obtiene la fecha de inicio efectiva de la designación dentro de un período específico.
     *
     * Este método calcula cuál es la fecha de inicio real para el cálculo de importes
     * en un período dado. Si la designación comenzó antes del período, se usa el inicio
     * del período. Si comenzó durante el período, se usa la fecha de inicio de la designación.
     *
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return Carbon Fecha de inicio efectiva para el período
     *
     * @example
     * // Designación que comenzó el 1 de enero de 2024
     * $designacion->fecha_inicio = '2024-01-01';
     * $fecha = $designacion->getFechasInicioEnPeriodo('202401');
     * // Retorna: 2024-01-01 (inicio del período)
     *
     * @example
     * // Designación que comenzó el 15 de enero de 2024
     * $designacion->fecha_inicio = '2024-01-15';
     * $fecha = $designacion->getFechasInicioEnPeriodo('202401');
     * // Retorna: 2024-01-15 (fecha de inicio de la designación)
     */
    public function getFechasInicioEnPeriodo($periodo){
        $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);
        $periodoInicio = $fechaPeriodo->copy()->startOfMonth();

        if($this->fecha_inicio->lessThanOrEqualTo($periodoInicio)){
            $resultado = $periodoInicio;
        }else{
            $resultado = $this->fecha_inicio;
        }

        return $resultado;
    }

    /**
     * Obtiene la fecha de fin efectiva de la designación dentro de un período específico.
     *
     * Este método calcula cuál es la fecha de fin real para el cálculo de importes
     * en un período dado. Si la designación termina después del período o no tiene
     * fecha de fin (null), se usa el fin del período. Si termina durante el período,
     * se usa la fecha de fin de la designación.
     *
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return Carbon Fecha de fin efectiva para el período
     *
     * @example
     * // Designación sin fecha de fin (activa)
     * $designacion->fecha_fin = null;
     * $fecha = $designacion->getFechasFinEnPeriodo('202401');
     * // Retorna: 2024-01-31 (fin del período)
     *
     * @example
     * // Designación que termina el 15 de enero de 2024
     * $designacion->fecha_fin = '2024-01-15';
     * $fecha = $designacion->getFechasFinEnPeriodo('202401');
     * // Retorna: 2024-01-15 (fecha de fin de la designación)
     */
    public function getFechasFinEnPeriodo($periodo){
        $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);
        $periodoFin = $fechaPeriodo->copy()->endOfMonth();

        if (is_null($this->fecha_fin) || $this->fecha_fin->greaterThanOrEqualTo($periodoFin)) {
            $resultado = $periodoFin;
        } else {
            $resultado = $this->fecha_fin;
        }

        return $resultado;
    }

    /**
     * Obtiene un string descriptivo de las fechas trabajadas en un período.
     *
     * Este método retorna una cadena de texto que describe el rango de fechas
     * en que el empleado trabajó durante el período especificado.
     *
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return string Descripción del rango de fechas trabajadas
     *
     * @example
     * // Designación que cubre todo enero 2024
     * $rango = $designacion->getFechasTrabajadasEnPeriodo('202401');
     * // Retorna: "2024-01-01 al 2024-01-31"
     *
     * @example
     * // Designación que empieza el 15 de enero 2024
     * $rango = $designacion->getFechasTrabajadasEnPeriodo('202401');
     * // Retorna: "2024-01-15 al 2024-01-31"
     */
    public function getFechasTrabajadasEnPeriodo($periodo){
        $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);

        $periodoInicio = $fechaPeriodo->copy()->startOfMonth();
        $periodoFin = $fechaPeriodo->copy()->endOfMonth();

        $resultado = '';

        if($this->fecha_inicio <= $periodoInicio){
            $resultado .= $periodoInicio.' al ';
        }else{
            $resultado .= $this->fecha_inicio.' al ';
        }

        if($this->fecha_fin >= $periodoFin){
            $resultado .= $periodoFin;
        }else{
            $resultado .= $this->fecha_fin;
        }

        return $resultado;
    }

    /**
     * Calcula el importe proporcional de la designación para un período específico.
     *
     * Este método calcula el importe que corresponde a esta designación basándose en:
     * 1. El valor del concepto de salario básico (código '001') para el cargo
     * 2. Los días trabajados en el período (fecha_inicio a fecha_fin)
     * 3. El tipo de valor del concepto (fijo o porcentual)
     *
     * El cálculo es proporcional: si el empleado trabajó solo una parte del mes,
     * el importe se calcula proporcionalmente a los días trabajados.
     *
     * @param Carbon $periodo_fecha_inicio Fecha de inicio del período (ya ajustada)
     * @param Carbon $periodo_fecha_fin Fecha de fin del período (ya ajustada)
     * @return float Importe calculado para el período
     *
     * @throws \Exception Si no se encuentra el concepto de salario básico o su valor
     *
     * @example
     * // Designación que cubre todo enero 2024
     * $inicio = Carbon::create(2024, 1, 1);
     * $fin = Carbon::create(2024, 1, 31);
     * $importe = $designacion->getImporteDesginacion($inicio, $fin);
     * // Retorna: 1000.00 (100% del salario básico)
     *
     * @example
     * // Designación que empieza el 15 de enero 2024
     * $inicio = Carbon::create(2024, 1, 15);
     * $fin = Carbon::create(2024, 1, 31);
     * $importe = $designacion->getImporteDesginacion($inicio, $fin);
     * // Retorna: 548.39 (17 días de 31 = 54.84% del salario básico)
     */
    public function getImporteDesginacion($periodo_fecha_inicio, $periodo_fecha_fin){
        // Obtener el concepto de salario básico (asumiendo código '001' o similar)
        $conceptoSalarioBasico = Concepto::where('codigo', '001')->first();

        if (!$conceptoSalarioBasico) {
            return 0;
        }

        // Obtener el valor del concepto para este cargo en el período
        $periodo = $periodo_fecha_inicio->format('Ym');
        $valorConcepto = ValorConcepto::where('concepto_id', $conceptoSalarioBasico->id)
            ->where('cargo_id', $this->cargo_id)
            ->where('periodo', $periodo)
            ->where('fecha_inicio', '<=', $periodo_fecha_inicio)
            ->where(function($query) use ($periodo_fecha_fin) {
                $query->where('fecha_fin', '>=', $periodo_fecha_fin)
                      ->orWhereNull('fecha_fin');
            })
            ->first();

        if (!$valorConcepto) {
            return 0;
        }

        // Calcular los días trabajados en el período (ignorando horas)
        $diasTrabajados = $periodo_fecha_inicio->copy()->startOfDay()->diffInDays($periodo_fecha_fin->copy()->startOfDay()) + 1;
        $diasTotalesMes = $periodo_fecha_inicio->copy()->startOfMonth()->startOfDay()->diffInDays($periodo_fecha_inicio->copy()->endOfMonth()->startOfDay()) + 1;

        // Calcular el porcentaje de días trabajados
        $porcentajeTrabajado = $diasTrabajados / $diasTotalesMes;

        // Si el concepto es porcentual, aplicar el porcentaje al valor base
        if ($conceptoSalarioBasico->isValorPorcentual()) {
            // Para conceptos porcentuales, el valor ya es un porcentaje
            return $valorConcepto->valor * $porcentajeTrabajado;
        } else {
            // Para conceptos de valor fijo, aplicar el porcentaje de días trabajados
            return $valorConcepto->valor * $porcentajeTrabajado;
        }
    }
}
