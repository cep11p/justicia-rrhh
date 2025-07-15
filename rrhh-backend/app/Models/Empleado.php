<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'persona_id',
        'fecha_ingreso',
        'titulo',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'titulo' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene la persona asociada a este empleado
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
    public function liquidacionEmpleados(): HasMany
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
     * Obtiene la designación vigente del empleado para un período específico (YYYYMM)
     *
     * Criterios de aceptación:
     * - Devuelve la designación vigente que cubre TODO el período especificado
     * - Si no hay designación que cubra todo el período, retorna null
     * - Si hay varias históricas que encajan, devuelve la que tiene fecha_inicio más reciente
     *
     * @param string $periodo Formato YYYYMM (ej: '202412')
     * @return Designacion|null La designación vigente o null si no existe
     * @throws \InvalidArgumentException Si el formato del período es inválido
     */
    public function getDesignacionParaPeriodo(string $periodo): ?Designacion
    {
        // Validar formato del período
        if (!preg_match('/^\d{6}$/', $periodo)) {
            throw new \InvalidArgumentException('El período debe tener formato YYYYMM (ej: 202412)');
        }

        // Convertir período a fechas (inicio y fin del mes)
        $fechaInicioPeriodo = Carbon::createFromFormat('Ym', $periodo)->startOfMonth();
        $fechaFinPeriodo = Carbon::createFromFormat('Ym', $periodo)->endOfMonth();

        // Buscar designación vigente para TODO el período
        // Comparar solo la fecha (sin hora)
        return $this->designaciones()
            ->whereDate('fecha_inicio', '<=', $fechaInicioPeriodo->toDateString())
            ->where(function ($query) use ($fechaFinPeriodo) {
                $query->whereNull('fecha_fin')
                      ->orWhereDate('fecha_fin', '>=', $fechaFinPeriodo->toDateString());
            })
            ->with(['estructuraOrganizativa', 'cargo'])
            ->orderBy('fecha_inicio', 'desc')
            ->first();
    }

    /**
     * Obtiene la designación vigente actual del empleado
     *
     * @return Designacion|null La designación vigente actual o null si no existe
     */
    public function getDesignacionActual(): ?Designacion
    {
        return $this->getDesignacionParaPeriodo(now()->format('Ym'));
    }

    /**
     * Verifica si el empleado tiene una designación vigente para un período
     *
     * @param string $periodo Formato YYYYMM (ej: '202412')
     * @return bool True si tiene designación vigente, false en caso contrario
     */
    public function tieneDesignacionEnPeriodo(string $periodo): bool
    {
        return $this->getDesignacionParaPeriodo($periodo) !== null;
    }

    /**
     * Obtiene todas las designaciones de un empleado en un rango de períodos
     *
     * @param string $periodoInicio Formato YYYYMM (ej: '202401')
     * @param string $periodoFin Formato YYYYMM (ej: '202412')
     * @return \Illuminate\Database\Eloquent\Collection Colección de designaciones
     */
    public function getDesignacionesEnRango(string $periodoInicio, string $periodoFin): \Illuminate\Database\Eloquent\Collection
    {
        // Validar formato de los períodos
        if (!preg_match('/^\d{6}$/', $periodoInicio) || !preg_match('/^\d{6}$/', $periodoFin)) {
            throw new \InvalidArgumentException('Los períodos deben tener formato YYYYMM (ej: 202412)');
        }

        // Convertir períodos a fechas
        $fechaInicio = Carbon::createFromFormat('Ym', $periodoInicio)->startOfMonth();
        $fechaFin = Carbon::createFromFormat('Ym', $periodoFin)->endOfMonth();

        // Buscar designaciones que se superpongan con el rango
        return $this->designaciones()
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->where(function ($q) use ($fechaInicio, $fechaFin) {
                    // Designación que inicia antes del rango y termina después del inicio del rango
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->where(function ($subQ) use ($fechaInicio) {
                          $subQ->whereNull('fecha_fin')
                               ->orWhere('fecha_fin', '>=', $fechaInicio);
                      });
                })->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                    // Designación que inicia dentro del rango
                    $q->where('fecha_inicio', '>=', $fechaInicio)
                      ->where('fecha_inicio', '<=', $fechaFin);
                });
            })
            ->with(['estructuraOrganizativa', 'cargo'])
            ->orderBy('fecha_inicio', 'asc')
            ->get();
    }

    // Método adicional para casos especiales
    public function getDesignacionEspecialParaPeriodo(string $periodo): ?Designacion
    {
        // Lógica para casos de ingresos/ascensos a mitad de mes
        // Solo para casos excepcionales
        return null; // Por ahora retorna null, implementar lógica específica cuando sea necesario
    }
}
