<?php

namespace App\Models;

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
}
