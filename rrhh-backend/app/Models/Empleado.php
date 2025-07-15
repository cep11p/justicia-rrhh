<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Obtiene la designación vigente para un período específico
     */
    public function getDesignacionParaPeriodo(string $periodo): ?Designacion
    {
        // Validar formato del período
        if (!preg_match('/^\d{6}$/', $periodo)) {
            throw new \InvalidArgumentException('El período debe tener formato YYYYMM (ej: 202412)');
        }

        $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);
        $fechaInicio = $fechaPeriodo->startOfMonth();
        $fechaFin = $fechaPeriodo->endOfMonth();

        return $this->designaciones()
            ->where('fecha_inicio', '<=', $fechaFin)
            ->where(function ($query) use ($fechaInicio) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', $fechaInicio);
            })
            ->orderBy('fecha_inicio', 'desc')
            ->first();
    }

    /**
     * Obtiene la designación actual del empleado
     */
    public function getDesignacionActual(): ?Designacion
    {
        $periodoActual = now()->format('Ym');
        return $this->getDesignacionParaPeriodo($periodoActual);
    }

    /**
     * Verifica si el empleado tiene designación en un período específico
     */
    public function tieneDesignacionEnPeriodo(string $periodo): bool
    {
        return $this->getDesignacionParaPeriodo($periodo) !== null;
    }

    /**
     * Obtiene las designaciones en un rango de fechas
     */
    public function getDesignacionesEnRango(string $fechaInicio, string $fechaFin): \Illuminate\Database\Eloquent\Collection
    {
        return $this->designaciones()
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                      ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                          $q->where('fecha_inicio', '<=', $fechaInicio)
                            ->where(function ($subQ) use ($fechaFin) {
                                $subQ->whereNull('fecha_fin')
                                     ->orWhere('fecha_fin', '>=', $fechaFin);
                            });
                      });
            })
            ->orderBy('fecha_inicio')
            ->get();
    }
}
