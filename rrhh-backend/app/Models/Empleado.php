<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Foreach_;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'persona_id',
        'fecha_ingreso',
        'legajo',
        'titulo',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'legajo' => 'string',
        'titulo' => 'string',
    ];

    /**
     * Obtiene la persona asociada al empleado
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Obtiene las designaciones del empleado
     */
    public function designaciones(): HasMany
    {
        return $this->hasMany(Designacion::class);
    }

    /**
     * Obtiene las liquidaciones del empleado
     */
    public function liquidaciones(): HasMany
    {
        return $this->hasMany(LiquidacionEmpleado::class);
    }

    /**
     * Obtiene el nombre completo del empleado
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->persona->nombre_completo ?? '';
    }

    /**
     * Obtiene todas las designaciones del empleado para un período específico.
     *
     * Este método filtra las designaciones que están activas durante el período
     * especificado, considerando las fechas de inicio y fin de cada designación.
     * Las designaciones se ordenan por fecha de inicio descendente (más recientes primero).
     *
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return \Illuminate\Database\Eloquent\Collection Colección de designaciones activas en el período
     *
     * @throws \InvalidArgumentException Si el formato del período es inválido
     *
     * @example
     * $empleado = Empleado::find(1);
     * $designaciones = $empleado->getDesignacionesParaPeriodo('202401');
     * // Retorna: Collection con designaciones activas en enero 2024
     *
     * @example
     * // Sin designaciones en el período
     * $designaciones = $empleado->getDesignacionesParaPeriodo('202401');
     * // Retorna: Collection vacía
     */
    public function getDesignacionesParaPeriodo(string $periodo): \Illuminate\Database\Eloquent\Collection
    {
        // Validar formato del período
        if (!preg_match('/^\d{6}$/', $periodo)) {
            throw new \InvalidArgumentException('El período debe tener formato YYYYMM (ej: 202412)');
        }

        $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);
        $fechaInicio = $fechaPeriodo->copy()->startOfMonth();
        $fechaFin = $fechaPeriodo->copy()->endOfMonth();

        return $this->designaciones()
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->where('fecha_inicio', '<=', $fechaFin)
                      ->where(function ($subQuery) use ($fechaInicio) {
                          $subQuery->whereNull('fecha_fin')
                                   ->orWhere('fecha_fin', '>=', $fechaInicio);
                      });
            })
            ->with(['estructuraOrganizativa', 'cargo'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }

    /**
     * Obtiene los importes calculados por designación para un período específico.
     *
     * Este método obtiene todas las designaciones del empleado para el período
     * y calcula el importe correspondiente a cada una, considerando los días
     * trabajados y el valor del concepto de salario básico.
     *
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return array Array con los importes calculados por designación
     *
     * @example
     * $empleado = Empleado::find(1);
     * $importes = $empleado->getImportePorDesginacion('202401');
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
    function getImportePorDesginacion($periodo) : array {
        $resultado = Array();

        $designaciones = $this->getDesignacionesParaPeriodo($periodo);

        $resultado = $this->calcularImporteDeDesignaciones($designaciones, $periodo);

        return $resultado;
    }

    /**
     * Calcula los importes para una colección de designaciones en un período específico.
     *
     * Este método procesa cada designación y calcula su importe correspondiente
     * basándose en los días trabajados y el valor del concepto de salario básico
     * del cargo asociado.
     *
     * @param \Illuminate\Database\Eloquent\Collection $designaciones Colección de designaciones
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return array Array con los importes calculados por designación
     *
     * @example
     * $designaciones = $empleado->designaciones;
     * $importes = $empleado->calcularImporteDeDesignaciones($designaciones, '202401');
     * // Retorna: [
     * //   [
     * //     'estructura_organizacional' => 'Administración',
     * //     'cargo' => 'Administrativo',
     * //     'periodo_fecha_inicio' => Carbon('2024-01-15'),
     * //     'periodo_fecha_fin' => Carbon('2024-01-31'),
     * //     'importe' => 548.39
     * //   ]
     * // ]
     */
    function calcularImporteDeDesignaciones($designaciones, $periodo) : array {
        $resultado = array();

        foreach ($designaciones as $designacion) {
            $periodo_fecha_inicio = $designacion->getFechasInicioEnPeriodo($periodo);
            $periodo_fecha_fin = $designacion->getFechasFinEnPeriodo($periodo);
            $designacion_importe = $designacion->getImporteDesginacion($periodo_fecha_inicio, $periodo_fecha_fin);
            $resultado[] = [
                "estructura_organizacional" => $designacion->estructuraOrganizativa->nombre,
                "cargo" => $designacion->cargo->nombre,
                "periodo_fecha_inicio" => $periodo_fecha_inicio,
                "periodo_fecha_fin" => $periodo_fecha_fin,
                "importe" => $designacion_importe
            ];
        }

        return $resultado;
    }
}
