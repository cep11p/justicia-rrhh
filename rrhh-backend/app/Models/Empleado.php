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
     * Obtiene todas las designaciones para un período específico
     * Retorna siempre una Collection:
     * - Collection vacía si no hay designaciones
     * - Collection con un elemento si hay una designación
     * - Collection con múltiples elementos si hay múltiples designaciones
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
}
